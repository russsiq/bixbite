<?php

namespace BBCMS\Http\Requests\Admin;

use BBCMS\Http\Requests\Request;

class ThemeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasRole('owner');
    }

    public function sanitize()
    {
        $input = $this->except(['_token', '_method', 'submit']);
        // $input['name'] = string_slug($input['name'], '_');

        return $this->replace($input)->all();
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    protected function validationData()
    {
        return $this->merge([
            // 'action' => $this->input('action') ?: 'setting',
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
            //
        ];
    }

}
