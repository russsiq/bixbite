<?php

namespace App\Http\Requests\Api\V1\File;

// Сторонние зависимости.
use App\Models\File;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Support\Str;

class Store extends FileRequest
{
    /**
     * Общий массив допустимых значений для правила `in:список_значений`.
     * @var array
     */
    protected $allowedForInRule = [
        'file_types' => [
            'archive' => [
                'ext' => ['7z', 'cab', 'rar', 'zip'],
                'mime' => ['application/x-gzip'],
            ],
            'audio' => [
                'ext' => ['mpga', 'mp3', 'ogg'],
                'mime' => [],
            ],
            'document' => [
                'ext' => ['doc', 'docx', 'ods', 'odt', 'pdf', 'ppt', 'rtf', 'xls', 'xlsx', 'xml'],
                'mime' => ['application/pdf'],
            ],
            'image' => [
                'ext' => ['bmp', 'gif', 'ico', 'jpe', 'jpeg', 'jpg', 'png', 'svg', 'svgz', 'tif', 'tiff', 'webp'],
                'mime' => [],
            ],
            'video' => [
                'ext' => ['3gp', 'avi', 'f4v', 'flv', 'm4a', 'm4v', 'mkv', 'mov', 'mp4', 'mpeg', 'qt', 'swf', 'wmv'],
                'mime' => [],
            ],

        ],

    ];

    /**
     * Подготовить данные для валидации.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        // Перед тем как собрать необходимую информацию о файле,
        // провалидиуем на его физическое присутствие.
        validator(
            $this->all(),
            [
                'file' => [
                    'required',
                    'file',
                ]
            ]
        )->validate();

        $file = $this->file('file');

        // Prepare variables.
        $mime_type = $file->getMimeType();
        $extension = $this->detectExtension($file);
        $type = $this->getFileType($mime_type, $extension);

        $title = $this->input('title', null) ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // NB.: En. and rus. x letter.
        $title = preg_replace('/[-_хx\d]+$/u', '', $title);
        $title = preg_replace('/\.tar$/', '', $title);
        $title = empty($title) ? Str::random(8) : $title;
        $properties = $this->input('properties', null);
        if ('image' == $type) {
            [$properties['width'], $properties['height']] = getimagesize($file->getPathname());
        }

        $this->replace([
            'user_id' => $this->user()->id,
            'attachment_id' => $this->input('attachment_id', null),
            'attachment_type' => $this->input('attachment_type', null),
            'disk' => $this->input('disk', 'public'),
            'category' => $this->input('category', 'default'),

            // Unstable data.
            'type' => $type,
            'name' => Str::slug($title).'_'.time(),
            'extension' => $extension,
            'mime_type' => $mime_type,
            'filesize' => $file->getSize(),
            'checksum' => md5_file($file->getPathname()),

            'title' => $title,
            'description' => Str::cleanHTML($this->input('description', null)),
            'properties' => $properties,
        ]);
    }

    /**
     * Получить массив правил валидации,
     * которые будут применены к запросу.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => [
                'bail',
                'required',
                'file',
                // imagealphablending or imagesavealpha == 500 error
                ('image' == $this->input('type')) ? 'dimensions:max_width=3840,max_height=2400' : '',

            ],

            'user_id' => [
                'required',
                'integer',
                'in:'.$this->user()->id,

            ],

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

            'disk' => [
                'required',
                'in:'.implode(',', array_keys(config('filesystems.disks'))),

            ],

            'category' => [
                'required',
                'string',
                'alpha_dash',

            ],

            // Unstable data.
            'type' => [
                'required',

            ],

            'mime_type' => [
                'required',
                'string',

            ],

            'name' => [
                'required',
                'string',
                'alpha_dash',

            ],

            'extension' => [
                'required',
                'string',

            ],

            'filesize' => [
                'required',
                'integer',

            ],

            'checksum' => [
                'required',
                'alpha_num',
                // На уникальность проверяем ниже, так как
                // нужна ссылка дубликата во всплывашке на фронте.
                // 'unique:files',

            ],

            'title' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\w\s\.\,\-\_\?\!\(\)\[\]]+$/u',

            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',

            ],

            'properties' =>  [
                'nullable',
                'array',

            ],

        ];
    }

    /**
     * Надстройка экземпляра валидатора.
     *
     * @param  ValidatorContract  $validator
     * @return void
     */
    public function withValidator(ValidatorContract $validator)
    {
        $validator->after(function (ValidatorContract $validator) {
            if ($duplicate = File::whereChecksum($this->input('checksum'))->first()) {
                $validator->errors()->add('checksum', sprintf(
                    trans('msg.already_exists'),
                    $duplicate->url,
                    $duplicate->title
                ));
            }
        });
    }

    protected function detectExtension($file): string
    {
        $original = $file->getClientOriginalExtension();
        $extension = $file->guessExtension() ?? $original;

        if ('gz' == $extension) {
            $extension = 'tar.gz';
        } elseif ('mpga' == $extension and 'mp3' == $original) {
            $extension = 'mp3';
        }

        return $extension;
    }

    protected function getFileType(string $mime, string $ext): string
    {
        $allowed = $this->allowedForInRule['file_types'];

        foreach ($allowed as $type => $term) {
            if (in_array($ext, $term['ext']) or in_array($mime, $term['mime'])) {
                return $type;
            }
        }

        return in_array($ext, ['php', 'exe']) ? 'forbidden' : 'other';
    }
}
