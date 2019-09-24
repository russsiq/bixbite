<?php

namespace BBCMS\Http\Requests\Api\V1\File;

use BBCMS\Models\File;

class Update extends FileRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function validationData()
    {
        return $this->merge([
                // 1 Do not change the filename. He may become unavailable.
                // 'name' => str_slug($this->input('title')).'_'.time(),

                // 2 Do not change the file title. Leave for validation.
                // 'title' => $this->input('title', null),

                // 3 Clean html tags in descroption.
                'description' => html_clean($this->input('description', null)),
            ])
            ->except([
                'name',
                '_token',
                '_method',
                'submit',
            ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // Always check the `attachment_type` first.
            'attachment_type' => [
                'nullable',
                'alpha_dash',
                'in:'.self::morphMap(),
            ],
            
            // After that, we check for a record in the database.
            'attachment_id' => [
                'bail',
                'nullable',
                'integer',
                'required_with:attachment_type',
                'exists:'.$this->input('attachment_type').',id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\w\s\.\,\-\_\?\!\(\)]+$/u',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }
}
