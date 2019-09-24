<?php

namespace BBCMS\Http\Requests\Api\V1\File;

use BBCMS\Models\File;
use BBCMS\Http\Requests\Request;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Relations\Relation;

class FileRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [
            // 'template' => __('Template'),
            // 'content' => __('Content'),
        ];
    }

    public function messages()
    {
        return [
            'file.dimensions' => sprintf(
                __('validation.dimensions_large'),
                $this->input('properties')['width'],
                $this->input('properties')['height']
            ),
        ];
    }

    /**
     * Get the morph map for polymorphic relations.
     *
     * @return array
     */
    protected static function morphMap()
    {
        return implode(',', array_keys(Relation::morphMap()));
    }
}
