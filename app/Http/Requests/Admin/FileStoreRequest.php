<?php

namespace BBCMS\Http\Requests\Admin;

use BBCMS\Models\File;
use BBCMS\Http\Requests\Request;

class FileStoreRequest extends Request
{
    // /**
    //  * Transform the error messages into JSON
    //  *
    //  * @param array $errors
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function response(array $errors)
    // {
    //     return response()->json($errors, 422);
    // }

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
        if (! $file = $this->file('file')) {
            throw new \Exception(__('validation.file'));
        }

        if (! $file->isValid()) {
            throw new \Exception($file->getErrorMessage());
        }

        // Prepare variables.
        $extension = $file->guessExtension() ?? $file->getClientOriginalExtension();
        $mime_type = $file->getMimeType();
        $type = self::getFileType($mime_type, $extension);

        $title = $this->input('title', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $title = preg_replace('/[-_х\d]+$/', '', $title);
        if ('image' == $type) {
            // Get info from image file.
            [$width, $height] = getimagesize($file->getPathname());
            $properties = compact('width', 'height');
        }

        return $this->replace([
            'user_id' => $this->user()->id,
            'attachment_id' => $this->input('attachment_id', null),
            'attachment_type' => $this->input('attachment_type', null),
            'disk' => $this->input('disk', 'public'),
            'category' => $this->input('category', 'default'),

            // Unstable data.
            'type' => $this->input('type', $type),
            'name' => str_slug($title).'_'.time(),
            'extension' => $extension,
            'mime_type' => $mime_type,
            'filesize' => $file->getClientSize(),
            'checksum' => md5_file($file->getPathname()),

            'title' => str_replace('.', '_', $title),
            'description' => $this->input('description', null),
            'properties' => $this->input('properties', $properties ?? null),

            'mass_uploading' => $this->input('mass_uploading', false),
        ])->all();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // $files = count($this->input('file'));
        //
        // foreach(range(0, $files) as $index) {
        //     $rules['photos.' . $index] = 'image|mimes:png,jpg,jpeg,gif,bmp|max:800';
        // }

        return [
            'file' => ['bail','required','file'],

            'user_id' => ['required','in:'.$this->user()->id],
            'attachment_id' => ['nullable','integer'],
            'attachment_type' => ['nullable','alpha_dash'],
            'disk' => ['required','in:'.implode(',', array_keys(config('filesystems.disks')))],
            'category' => ['required','string','alpha_dash'],

            // Unstable data.
            'type' => ['nullable'],
            'mime_type' => ['required','string'],
            'name' => ['required','string','alpha_dash'],
            'extension' => ['required','string','alpha_dash'],
            'filesize' => ['required','integer'],
            'checksum' => ['required','alpha_num'], // 'unique:files' - below validate

            'title' => ['required','string','max:255','regex:/^[\w\s\,\-\_\?\!]+$/u'],
            'description' => ['nullable','string'],
            'properties' =>  ['nullable','array'],

            // 'title' => ['sometimes', 'string', 'max:125', 'regex:/^[\pL\s]+$/u', ],
            // 'descr' => ['nullable', 'string', 'max:500', ],
            // 'is_completed' => ['sometimes', 'boolean', ],
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
            if ($duplicate = File::whereChecksum($this->input('checksum'))->first()) {
                $validator->errors()->add('checksum', sprintf(
                    __('msg.already_exists'),
                    $duplicate->url,
                    $duplicate->title
                ));
            }
        });

        return $validator;
    }

    protected static function getFileType(string $mim, string $ext)
    {
        foreach (self::getAllowedFileType() as $key => $term) {
            if (in_array($ext, $term['ext']) or in_array($mim, $term['mim'])) {
                return $key;
            }
        }

        return in_array($ext, ['php','exe']) ? 'forbidden' : 'other';
    }

    protected static function getAllowedFileType()
    {
        return [
           'archive' => [
               'mim' => ['application/x-gzip'],
               'ext' => ['7z','cab','rar','zip'],
           ],
           'audio' => [
               'mim' => [],
               'ext' => ['mpga','mp3','ogg'],
           ],
           'document' => [
               'mim' => ['application/pdf'],
               'ext' => ['doc','docx','ods','odt','pdf','ppt','rtf','xls','xlsx','xml'],
           ],
           'image' => [
               'mim' => [],
               'ext' => ['bmp','gif','ico','jpe','jpeg','jpg','png','svg','svgz','tif','tiff'],
           ],
           'video' => [
               'mim' => [],
               'ext' => ['3gp','avi','f4v','flv','m4a','m4v','mkv','mov','mp4','mpeg','qt','swf','wmv'],
           ],
       ];
    }
}
