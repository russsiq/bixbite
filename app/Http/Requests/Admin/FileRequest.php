<?php

namespace BBCMS\Http\Requests\Admin;

use BBCMS\Models\File;
use BBCMS\Http\Requests\Request;

class FileRequest extends Request
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

    // public function sanitize()
    // {
    //     $input = $this->except(['_token', '_method', 'submit']);
    //     $input['category'] = 'default';
    //
    //     return $this->replace($input)->all();
    // }

    public function sanitize()
    {
        // Prepare variables.
        $old_title = $this->route()->parameters['file']->getAttribute('title');

        if ($old_title == $this->input('title', $old_title)) {
            return $this->all();
        }

        return $this->replace([
            'name' => str_slug($this->input('title')).'_'.time(),
            'title' => $this->input('title'), // $name, // NOT CHANGE, skeep to validating.
        ])->all();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'attachment_id' => ['nullable','integer'],
            'attachment_type' => ['nullable','alpha_dash'],
            'name' => ['sometimes','required','string','alpha_dash'],
            'title' => ['required','string','max:255','regex:/^[\w\s-_\.]+$/u'],
            'description' => ['nullable','string'],
        ];
    }


    /**
    * Configure the validator instance.
    *
    * @param  \Illuminate\Validation\Validator  $validator
    * @return void
    */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            //
        });

        return $validator;
    }
}
