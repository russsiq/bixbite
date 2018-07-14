<?php

namespace BBCMS\Http\Controllers\Admin;

use BBCMS\Models\Tag;
use BBCMS\Models\Article;
use BBCMS\Models\XField;
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

    public function __construct(Article $model, Category $categories, Tag $tags, XField $x_fields)
    {
        parent::__construct();
        $this->authorizeResource(Article::class);

        $this->tags = $tags;
        $this->model = $model;
        $this->x_fields = $x_fields->fields()->where('extensible', $model->getTable());
        $this->categories = $categories->getCachedCategories()->nested();
    }

    public function index()
    {
        // Если название метода контроллера совпадает с названием метода политики
        $this->authorize($this->model);

        $articles = $this->model
            ->withCount('comments')
            ->with([
                'categories:categories.id,categories.title,categories.slug',
                'user:users.id,users.name',
            ])
            ->orderBy('id', 'desc')
            ->paginate(setting('articles.paginate', 8));

        $f = $filters = [];
        // // 1 Get
        // $f = request()->get('f');
        // // 2 Available attribute types.
        // $types = ['integer', 'string', 'boolean', 'date', 'datetime', 'timestamp'];
        // // 3 Get all attributes from first row in model with $appends attributes.
        // $attributes = array_keys($this->model->first()->attributesToArray());
        // // 3 Create available criteria of filters.
        // $filters = [];
        // foreach ($attributes as $attribute)
        //     if ($this->model->hasCast($attribute, $types))
        //         $filters[] = $attribute;

        return $this->renderOutput('index', compact('articles', 'filters', 'f'));
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
        $article = $this->model->fill($request->all());

        foreach ($this->x_fields->pluck('name') as $x_field) {
            $article->{$x_field} = $request->{$x_field};
        }

        $article->save();

        // Image.
        if ($request->image_id) {
            // Get related Model.
            $image = $article->files()->getRelated();
            // Create new acosiation. associate($request->image_id)
            $image->whereId($request->image_id)->update([
                'attachment_type' => $article->getMorphClass(),
                'attachment_id' => $article->id
            ]);
        }

        $article->categories()->sync($request->categories);

        // Tags. Always synchronise !!!
        // Deleted tags with empty relations
        // Creat new tag, if not exists
        $this->tags->synchronise($request->tags, $article);

        return redirect()->route('admin.articles.index')->withStatus(sprintf(
                __('msg.store'), $article->url, route('admin.articles.edit', $article)
            ));
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
        foreach ($this->x_fields->pluck('name') as $x_field) {
            $article->{$x_field} = $request->{$x_field};
        }

        $article->update($request->all());

        // Image. Only if empty image_id, then keep previos image_id.
        if ($request->image_id) {
            // Get related Model.
            $image = $article->files()->getRelated();
            // Delete acosiation with old. dissociate($request->image_id)
            if ($article->image_id) {
                $image->whereId($article->image_id)->update([
                    'attachment_type' => null,
                    'attachment_id' => null
                ]);
            }
            // Create new acosiation. associate($request->image_id)
            $image->whereId($request->image_id)->update([
                'attachment_type' => $article->getMorphClass(),
                'attachment_id' => $article->id
            ]);
        }

        // Categories. Only if empty category, then keep previos category.
        if ($request->categories) {
            $article->categories()->sync($request->categories);
        }

        // Tags. Always sync !!! Deleted tags with empty relations.
        $article->tags()->getModel()->synchronise($request->tags, $article);

        return redirect()->route('admin.articles.index')->withStatus(sprintf(
                __('msg.update'), $article->url, route('admin.articles.edit', $article)
            ));
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
