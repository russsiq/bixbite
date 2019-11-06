<?php

namespace BBCMS\Http\Requests\Setup\SystemInstall;

use BBCMS\Models\User;
use BBCMS\Http\Requests\Request;
use BBCMS\Exceptions\InstallerFailed;

class CommonRequest extends Request
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'APP_NAME' => ['required', 'string', ],
            'APP_THEME' => ['required', 'string', 'in:'.implode(',', select_dir('themes')), ],
            'name' => ['required', 'string', 'between:3,255', ],
            'email' => ['required', 'string', 'between:6,255', 'email', 'unique:users', ],
            'password' => ['required', 'string', 'between:6,255', ],
            'original_theme' => ['sometimes', 'boolean', ],
            'test_seed' => ['sometimes', 'boolean', ],
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
            'APP_NAME' => __('APP_NAME'),
            'APP_THEME' => __('APP_THEME'),
            'name' => __('common.name'),
            'email' => __('common.email'),
            'password' => __('common.password'),
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

                    // 1 Copy theme
                    if (empty($data['original_theme'])) {
                        $theme = string_slug($data['APP_NAME']);
                        $dest = app()->resourcePath('themes' . DS . $theme);
                        if (is_dir($dest)) {
                            throw new InstallerFailed(str_replace(':theme', $theme, __('msg.theme_exists')));
                        }
                        $files = new \RecursiveIteratorIterator(
                            new \RecursiveDirectoryIterator(
                                app()->resourcePath('themes' . DS . $data['APP_THEME']),
                                \RecursiveDirectoryIterator::SKIP_DOTS
                            ),
                            \RecursiveIteratorIterator::SELF_FIRST
                        );
                        foreach ($files as $file) {
                            $pathname = $dest . DS . $files->getSubPathName();
                            $file->isDir() ? mkdir($pathname, 0755, true) : copy($file, $pathname);
                        }
                    }

                    // 2 Creat symlink to uploads dir
                    clearstatcache(true, $uploads_path = app()->basePath('uploads'));
                    if (! file_exists($uploads_path)) {
                        symlink(
                            app()->basePath('storage' . DS . 'app' . DS . 'uploads'),
                            $uploads_path
                        );
                    }

                    // 3 Manipulation of the database
                    \DB::beginTransaction();

                    // 3.1 Seed DB
                    $exitCode = \Artisan::call('db:seed', ['--force' => true]);
                    $outputSeed = \Artisan::output();
                    session()->flash('flash_output_seed', $outputSeed);

                    // 3.2 Creat user
                    \DB::table('users')->insert([
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'password' => bcrypt($data['password']),
                        'role' => 'owner',
                        'last_ip' => request()->ip(),
                        'created_at' => date('Y-m-d H:i:s'),
                        'email_verified_at' => date('Y-m-d H:i:s'),
                    ]);

                    // 3.3 Seed test content DB
                    if (! empty($data['test_seed'])) {
                        $exitCode = \Artisan::call('db:seed', ['--force' => true, '--class' => 'TestContentSeeder']);
                        $outputTestSeed = \Artisan::output();
                        session()->flash('flash_output_test_seed', $outputTestSeed);
                    }

                    // 4 Save config files

                    // Finaly set to `.env` variables
                    $this->merge([
                        // Set to new copied theme
                        'APP_THEME' => $theme ?? $data['APP_THEME'],
                        // Set to BBCMS is install complete
                        'APP_INSTALLED_AT' => date('Y-m-d H:i:s'),
                        'APP_DEBUG' => 'false',
                        // Mail settings
                        'MAIL_FROM_NAME' => $data['name'],
                        'MAIL_FROM_ADDRESS' => $data['email'],
                    ]);

                    \DB::commit();
                }
                catch (\InstallerFailed $e) {
                    \DB::rollback();
                    $validator->errors()->add('common', $e->getMessage());
                }

            });
        }

        return $validator;
    }
}
