<?php

namespace BBCMS\Http\Requests\Setup\SystemInstall;

use BBCMS\Http\Requests\Request;
use BBCMS\Exceptions\InstallerFailed;

class DatabaseRequest extends Request
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

    public function sanitize()
    {
        $input = $this->except(['_token', '_method', /*'created_at', 'updated_at', 'deleted_at',*/ 'submit']);

        // $input['title'] = filter_var($input['title'], FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);

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
            'DB_CONNECTION' => 'mysql',
            'DB_HOST' => $this->input('DB_HOST', '127.0.0.1'),
            'DB_PORT' => $this->input('DB_PORT', '3306'),
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
            'DB_CONNECTION' => ['bail', 'required', 'string', 'in:mysql', ],
            'DB_HOST' => ['bail', 'required', 'string', ],
            'DB_PORT' => ['bail', 'required', 'integer', ],
            'DB_DATABASE' => ['bail', 'required', 'string', ],
            'DB_PREFIX' => ['bail', 'required', 'string', ],
            'DB_USERNAME' => ['bail', 'required', 'string', ],
            'DB_PASSWORD' => ['bail', 'nullable', 'string', ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'DB_CONNECTION' => __('DB_CONNECTION'),
            'DB_HOST' => __('DB_HOST'),
            'DB_PORT' => __('DB_PORT'),
            'DB_DATABASE' => __('DB_DATABASE'),
            'DB_PREFIX' => __('DB_PREFIX'),
            'DB_USERNAME' => __('DB_USERNAME'),
            'DB_PASSWORD' => __('DB_PASSWORD'),
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
        if ($validator->passes()) {
            $validator->after(function ($validator) {

                try {
                    // get validated data
                    $data = $this->validated();

                    // Set temporary DB connection
                    config(['database.connections.mysql' => [
                        'driver' => 'mysql',
                        'host' =>  $data['DB_HOST'],
                        'database' => $data['DB_DATABASE'],
                        'prefix' => $data['DB_PREFIX'],
                        'username' => $data['DB_USERNAME'],
                        'password' => $data['DB_PASSWORD'],
                        'charset' => 'utf8',
                        'collation' => 'utf8_unicode_ci',
                        'strict' => true,
                        'engine' => 'InnoDB',
                    ]]);

                    // Check DB connection and exists table
                    \DB::reconnect('mysql');
                    \DB::setTablePrefix($data['DB_PREFIX']);
                    if (is_null(\DB::connection('mysql')->getDatabaseName())) {
                        throw new InstallerFailed(__('msg.not_dbconnect'));
                    }

                    // 3 Manipulation of the database
                    \DB::beginTransaction();

                    // Make migrate
                    $exitCode = \Artisan::call('migrate', ['--force' => true]);
                    $outputMigrate = \Artisan::output();
                    session()->flash('flash_output_migrate', $outputMigrate);

                    \DB::commit();
                } catch (\InstallerFailed $e) {
                    \DB::rollback();
                    $validator->errors()->add('database', $e->getMessage());
                }

            });
        }

        return $validator;
    }
}
