<?php

namespace BBCMS\Http\Requests\Admin;

use BBCMS\Models\Setting;
use BBCMS\Http\Requests\Request;

class SettingModuleRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return false;
        return true;
    }

    public function sanitize()
    {
        $input = $this->except(['_token', '_method', 'submit']);

        return $this->replace($input)->all();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

        ];
    }

    // /**
    //  * Get custom messages for validator errors.
    //  *
    //  * @return array
    //  */
    // public function messages()
    // {
    //     return [
    //         'agree.*' => __('msg.not_accept_licence'),
    //     ];
    // }

}
