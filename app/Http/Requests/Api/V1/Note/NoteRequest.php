<?php

namespace BBCMS\Http\Requests\Api\V1\Note;

use BBCMS\Models\Note;
use BBCMS\Http\Requests\BaseFormRequest;

use Illuminate\Validation\Rule;

class NoteRequest extends BaseFormRequest
{
    /**
     * Получить данные из запроса для валидации.
     *
     * @return array
     */
    public function validationData()
    {
        $input = [];

        $input['user_id'] = $this->get('user_id', null);
        $input['image_id'] = $this->get('image_id', null);
        $input['title'] = $this->get('title', null);
        $input['slug'] = string_slug($input['title']);
        $input['description'] = teaser($this->get('description', null), 500);
        $input['is_completed'] = $this->get('is_completed', false);

        return $this->replace($input)->all();
    }

    public function rules()
    {
        return [
            'title' => [
                'required',
                'string',
                'max:225',
                'regex:/^[\w\s\.\,\-\_\?\!\(\)\[\]]+$/u',
            ],

            'slug' => [
                'required',
                'string',
                'max:225',
                'alpha_dash',
                Rule::unique('notes')->ignore($this->note),
            ],

            'description' => [
                'required',
                'string',
                'max:500',
            ],

            'is_completed' => [
                'required',
                'boolean',
            ],

            // Relations types.
            'user_id' => [
                'required',
                Rule::in(auth('api')->user()->id),
            ],

            'image_id' => [
                'nullable',
                'exists:files,id',
            ],
        ];
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
            // // 'template.required' => __('msg.template.required'),
            // 'content.required' => __('msg.content.required'),
        ];
    }
}
