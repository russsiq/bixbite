<?php

namespace BBCMS\Http\Controllers\Admin;

use BBCMS\Models\Tag;
use BBCMS\Models\Article;
use BBCMS\Models\Category;
use BBCMS\Http\Requests\Admin\ArticleRequest;
use BBCMS\Http\Requests\Admin\ArticlesRequest;
use BBCMS\Http\Controllers\Admin\AdminController;

class ArticlesController extends AdminController
{
    protected $tags;
    protected $model;
    protected $x_fields;
    protected $categories;
    protected $template = 'articles';

    public function __construct(Article $model, Category $categories, Tag $tags)
    {
        parent::__construct();
        $this->authorizeResource(Article::class);

        $this->tags = $tags;
        $this->model = $model;
        $this->x_fields = $model->x_fields;
        $this->categories = $categories->getCachedCategories()->nested();
    }

    public function index()
    {
        $this->authorize($this->model);

        $articles = $this->model
            ->withCount(['comments'])
            ->with([
                'categories:categories.id,categories.title,categories.slug',
                'user:users.id,users.name',
            ])
            ->orderBy('id', 'desc')
            ->paginate(setting('articles.paginate', 8));

        return $this->renderOutput('index', compact('articles'));
    }

    public function create()
    {
        if (! $this->categories->count()) {
            return redirect()->route('admin.categories.index')
                ->withErrors([__('msg.category_empty')]);
        }

        return $this->renderOutput('create', [
            'article' => [],
            'delimiter' => '',
            'categories_items' => $this->categories,
            'tags' => '',
            'x_fields' => $this->x_fields,
        ]);
    }

    public function store(ArticleRequest $request)
    {
        $article = $this->model->create($request->all());
        $article->categories()->sync($request->categories);
        $article->tags()->sync(array_map(
            function (string $tag) {
                return $this->tags->firstOrCreate(['title' => $tag])->id;
            }, $request->tags
        ));

        if ('published' == $article->state) {
            $message = sprintf(
                __('msg.store_published'), $article->url, route('admin.articles.edit', $article)
            );
        } else {
            $message = sprintf(
                __('msg.store'), route('admin.articles.edit', $article)
            );
        }

        return redirect()->route('admin.articles.index')->withStatus($message);
    }

    public function edit(Article $article)
    {
        if (! $this->categories->count()) {
            return redirect()->route('admin.categories.index')
                ->withErrors([__('msg.category_empty')]);
        }

        $article->when($article->image_id, function ($query) {
            $query->with(['image']);
        });

        return $this->renderOutput('edit', [
            'article' => $article,
            'delimiter' => '',
            'categories_items' => $this->categories,
            'tags' => $article->tags->pluck('title')->implode(', '),
            'x_fields' => $this->x_fields,
        ]);
    }

    public function update(ArticleRequest $request, Article $article)
    {
        $article->update($request->all());
        $article->categories()->sync($request->categories);
        $article->tags()->sync(array_map(
            function (string $tag) {
                return $this->tags->firstOrCreate(['title' => $tag])->id;
            }, $request->tags
        ));

        if ('published' == $article->state) {
            $message = sprintf(
                __('msg.update_published'), $article->url, route('admin.articles.edit', $article)
            );
        } else {
            $message = sprintf(
                __('msg.update'), route('admin.articles.edit', $article)
            );
        }

        return redirect()->route('admin.articles.index')->withStatus($message);
    }

    public function destroy(Article $article)
    {
        $article->delete();

        return redirect()->route('admin.articles.index')->withStatus(__('msg.destroy'));
    }

    /**
     * Mass updates to Article.
     *
     * @param  \BBCMS\Http\Requests\Admin\ArticlesRequest  $request
     * @param  \BBCMS\Models\Article  $articles
     * @return \Illuminate\Http\Response
     */
    public function massUpdate(ArticlesRequest $request)
    {
        $this->authorize('otherUpdate', $this->model);

        $articles = $this->model->whereIn('id', $request->articles);
        $messages = [];

        switch ($request->mass_action) {
            case 'published':
                if (! $articles->update(['state' => 'published'])) {
                    $messages[] = 'unable to published';
                }
                break;
            case 'unpublished':
                if (! $articles->update(['state' => 'unpublished'])) {
                    $messages[] = 'unable to unpublished';
                }
                break;
            case 'draft':
                if (! $articles->update(['state' => 'draft'])) {
                    $messages[] = 'unable to draft';
                }
                break;
            case 'on_mainpage':
                if (! $articles->update(['on_mainpage' => 1])) {
                    $messages[] = 'unable to mainpage';
                }
                break;
            case 'not_on_mainpage':
                if (! $articles->update(['on_mainpage' => 0])) {
                    $messages[] = 'unable to not_on_mainpage';
                }
                break;
            case 'allow_com':
                if (! $articles->update(['allow_com' => 1])) {
                    $messages[] = 'unable to allow_com';
                }
                break;
            case 'disallow_com':
                if (! $articles->update(['allow_com' => 0])) {
                    $messages[] = 'unable to disallow_com';
                }
                break;
            case 'currdate':
                $articles->timestamps = false;
                if (! $articles->update(['created_at' => date('Y-m-d H:i:s'), 'updated_at' => null])) {
                    $messages[] = 'unable to currdate';
                }
                $articles->timestamps = true;
                break;
            case 'delete':
                if (! $articles->get()->each->delete()) {
                    $messages[] = 'unable to delete';
                }
                break;
            case 'delete_drafts':
                if (! $articles->where('state', 'draft')->get()->each->delete()) {
                    $messages[] = 'unable to delete_drafts';
                }
                break;
        }

        if (! empty($messages)) {
            // return redirect()->back()->withErrors($messages);
            return redirect()->route('admin.articles.index')->withStatus('msg.complete_but_null');
        } else {
            return redirect()->route('admin.articles.index')->withStatus('msg.complete');
        }
    }
}
