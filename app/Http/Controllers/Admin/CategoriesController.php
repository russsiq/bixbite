<?php

namespace BBCMS\Http\Controllers\Admin;

use BBCMS\Models\Category;
use BBCMS\Http\Requests\CategoriesRequest;
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

    public function store(CategoriesRequest $request)
    {
        $this->model->create($request->all());

        return redirect()->route('admin.categories.index')->with('status', 'msg.complete_create');
    }

    public function edit(Category $category)
    {
        return $this->renderOutput('edit', [
            'template_list' => select_dir('custom_views', true),
            'category' => $category,
        ]);
    }

    public function update(CategoriesRequest $request, Category $category)
    {
        $category->update($request->all());

        return redirect()->route('admin.categories.index')->with('status', __('msg.complete_attributes', ['title' => $category->title]));
    }

    public function destroy(Category $category)
    {
        if ($this->model->where('parent_id', $category->id)->count() or $category->articles->count()) {
            return redirect()->back()->withErrors(['msg.not_empty']);
        }
        
        $category->articles()->detach();
        $category->delete();

        return redirect()->route('admin.categories.index')->with('status', 'msg.complete_destroy');
    }

    public function positionReset(Request $request)
    {
        $this->authorize('otherUpdate', Category::class);
        $this->model->positionReset();

        return redirect()->route('admin.categories.index')->with('status', 'msg.complete_position_reset');
    }

    public function positionUpdate(Request $request)
    {
        $this->authorize('otherUpdate', Category::class);
        $request->validate([
            'list' => 'required|array',
        ]);

        if ($this->model->positionUpdate($request)) {
            return response()->json(['status' => true, 'message' => __('msg.complete_position_update')], 200);
        }

        return response()->json(['status' => false, 'message' => __('msg.not_complete')], 200);
    }
}
