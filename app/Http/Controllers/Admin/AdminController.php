<?php

namespace BBCMS\Http\Controllers\Admin;

use BBCMS\Models\Article;
use BBCMS\Http\Controllers\BaseController;

class AdminController extends BaseController
{
    protected $template;

    public function __construct()
    {
        // Duplicating middleware
        $this->middleware(['auth', 'can:global.admin']);
    }

    /**
     * Render output to html string.
     *
     * @param  string $template
     * @param  array  $vars
     * @return mixed
     * @NB Overwriting the parent method.
     */
    protected function renderOutput(string $template, array $vars = [])
    {
        $vars = array_merge($vars, [
            'unpublished' => Article::select(['id', 'user_id', 'state'])
            ->when('owner' != user('role'), function ($query) {
                // Show unpublished articles only to current user.
                $query->where('user_id', user('id'));
            })
            ->where('state', '<>', 'published')
            ->get(),
        ]);

        return parent::renderOutput($template, $vars);
    }
}
