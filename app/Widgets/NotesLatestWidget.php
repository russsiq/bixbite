<?php

namespace BBCMS\Widgets;

use BBCMS\Models\Note;
use BBCMS\Support\WidgetAbstract;

class NotesLatestWidget extends WidgetAbstract
{
    protected $cacheTime = 0;
    protected $template = 'notes.widget_latest';

    public function execute()
    {
        return [
            'title' => $this->params['widget_title'] ?? __('notes'),
            'items' => Note::where('user_id', user('id'))
                ->whereNull('is_completed')
                ->orWhere('is_completed', '<>', 1)
                ->get(),
        ];
    }

    protected function default()
    {
        return [

        ];
    }

    protected function rules()
    {
        return [

        ];
    }
}
