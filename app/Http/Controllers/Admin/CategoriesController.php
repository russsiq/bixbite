<?php

namespace BBCMS\Http\Controllers\Admin;

use BBCMS\Models\Category;
use BBCMS\Http\Requests\Admin\CategoryRequest;
use BBCMS\Http\Controllers\Admin\AdminController;

use Illuminate\Http\Request;

class CategoriesController extends AdminController
{
    protected $model;
    protected $template = 'categories';

    public function __construct(Category $model)
    {
        parent::__construct();
        $this->authorizeResource(Category::class);

        $this->model = $model;

        // Always flush cache. Otherwise why go to this section?
        $model->cacheForgetByKeys();
    }

    public function index()
    {
        $this->authorize(Category::class);

        $categories = $this->model
            ->orderByRaw('ISNULL(`position`), `position` ASC')
            ->withCount('articles')
            ->get()
            ->nested();

        return $this->renderOutput('index', compact('categories'));
    }

    public function create()
    {
        return $this->renderOutput('create', [
            'template_list' => select_dir('custom_views', true),
            'category' => [],
        ]);
    }

    public function store(CategoryRequest $request)
    {
        $category = $this->model->create($request->all());

        // Image.
        if ($request->image_id) {
            // Get related Model.
            $image = $category->files()->getRelated();
            // Create new acosiation. associate($request->image_id)
            $image->whereId($request->image_id)->update([
                'attachment_type' => $category->getMorphClass(),
                'attachment_id' => $category->id
            ]);
        }

        return redirect()->route('admin.categories.index')->withStatus(sprintf(
                __('msg.store'), $category->url, route('admin.categories.edit', $category)
            ));
    }

    public function edit(Category $category)
    {
        $category->when($category->image_id, function ($query) {
            $query->with(['image']);
        });

        return $this->renderOutput('edit', [
            'template_list' => select_dir('custom_views', true),
            'category' => $category,
        ]);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->all());

        // Image. Only if empty image_id, then keep previos image_id.
        if ($request->image_id) {
            // Get related Model.
            $image = $category->files()->getRelated();
            // Delete acosiation with old. dissociate($request->image_id)
            if ($category->image_id) {
                $image->whereId($category->image_id)->update([
                    'attachment_type' => null,
                    'attachment_id' => null
                ]);
            }
            // Create new acosiation. associate($request->image_id)
            $image->whereId($request->image_id)->update([
                'attachment_type' => $category->getMorphClass(),
                'attachment_id' => $category->id
            ]);
        }

        return redirect()->route('admin.categories.index')->withStatus(sprintf(
                __('msg.update'), $category->url, route('admin.categories.edit', $category)
            ));
    }

    public function destroy(Category $category)
    {
        if ($this->model->where('parent_id', $category->id)->count() or $category->articles->count()) {
            return redirect()->back()->withErrors(['msg.not_empty']);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->withStatus(__('msg.destroy'));
    }

    public function positionReset(Request $request)
    {
        $this->authorize('otherUpdate', Category::class);
        $this->model->positionReset();

        return redirect()->route('admin.categories.index')->withStatus('msg.position_reset');
    }

    public function positionUpdate(Request $request)
    {
        $this->authorize('otherUpdate', Category::class);
        $request->validate([
            'list' => 'required|array',
        ]);

        if ($this->model->positionUpdate($request)) {
            return response()->json(['status' => true, 'message' => __('msg.position_update')], 200);
        }

        return response()->json(['status' => false, 'message' => __('msg.not_complete')], 200);
    }
}
