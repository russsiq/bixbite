<?php

namespace App\Actions\Attachment;

use App\Actions\ActionAbstract;
use App\Models\Attachment;
use App\Models\Contracts\AttachableContract;
use App\Models\User;
use App\Rules\SqlTextLengthRule;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Validation\Rule;

abstract class AttachmentActionAbstract extends ActionAbstract
{
    protected ?AttachableContract $attachable = null;
    protected ?Attachment $attachment = null;
    protected ?User $author = null;

    protected $allowedFileTypes = [
        'archive' => [
            'extension' => ['7z', 'cab', 'rar', 'zip',],
            'mime_type' => [],
        ],
        'audio' => [
            'extension' => ['mp3', 'ogg',],
            'mime_type' => ['audio/webm',],
        ],
        'document' => [
            'extension' => ['doc', 'docx', 'epub', 'fb2', 'pdf', 'ppt', 'pptx', 'rtf', 'xls', 'xlsx', 'xml',],
            'mime_type' => [],
        ],
        'image' => [
            'extension' => ['bmp', 'gif', 'ico', 'jpeg', 'jpg', 'png', 'svg', 'svgz', 'tif', 'tiff', 'webp',],
            'mime_type' => ['image/webp',],
        ],
        'video' => [
            'extension' => ['3gp', 'avi', 'mp4',],
            'mime_type' => ['video/webm',],
        ],
    ];

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    abstract protected function rules(): array;

    /**
     * Get the validation rules used to validate condition of uploaded file.
     *
     * @return array
     */
    protected function conditionFileRules(): array
    {
        if ($this->attachment instanceof Attachment) {
            return [];
        }

        return [
            Attachment::UPLOADED_FILE => [
                'bail',
                'required',
                'file',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `user_id` field.
     *
     * @return array
     */
    protected function userIdRules(): array
    {
        if ($this->attachment instanceof Attachment) {
            return [];
        }

        return [
            'user_id' => [
                'bail',
                'required',
                'integer',
                'min:1',
                "size:{$this->user()->getAuthIdentifier()}",
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `attachable_type` field.
     *
     * Check before validation `attachable_id`.
     *
     * @return array
     */
    protected function attachableTypeRules(): array
    {
        if ($this->attachment instanceof Attachment) {
            return [];
        }

        return [
            'attachable_type' => [
                'bail',
                'required',
                'alpha_dash',
                Rule::in(array_keys(Relation::morphMap()))
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `attachable_id` field.
     *
     * @return array
     */
    protected function attachableIdRules(): array
    {
        if ($this->attachment instanceof Attachment) {
            return [];
        }

        return [
            'attachable_id' => [
                'bail',
                'required',
                'integer',
                // 'exists:'.$this->input('attachable_type').',id',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `title` field.
     *
     * @return array
     */
    protected function titleRules(): array
    {
        return [
            'title' => [
                'bail',
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `description` field.
     *
     * @return array
     */
    protected function descriptionRules(): array
    {
        return [
            'description' => [
                'nullable',
                'string',
                $this->container->make(SqlTextLengthRule::class),
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `disk` field.
     *
     * @return array
     */
    protected function diskRules(): array
    {
        if ($this->attachment instanceof Attachment) {
            return [];
        }

        $disks = $this->container->make('config')
            ->get('filesystems.disks', []);

        return [
            'disk' => [
                'required',
                Rule::in(array_keys($disks)),
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `folder` field.
     *
     * @return array
     */
    protected function folderRules(): array
    {
        if ($this->attachment instanceof Attachment) {
            return [];
        }

        return [
            'folder' => [
                'required',
                'string',
                'alpha_dash',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `type` field.
     *
     * @return array
     */
    protected function typeRules(): array
    {
        if ($this->attachment instanceof Attachment) {
            return [];
        }

        return [
            'type' => [
                'required',
                'string',
                'alpha_dash',
                Rule::in(array_keys($this->allowedFileTypes)),
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `name` field.
     *
     * @return array
     */
    protected function nameRules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'alpha_dash',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `extension` field.
     *
     * @return array
     */
    protected function extensionRules(): array
    {
        if ($this->attachment instanceof Attachment) {
            return [];
        }

        return [
            'extension' => [
                'required',
                'string',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `mime_type` field.
     *
     * @return array
     */
    protected function mimeTypeRules(): array
    {
        if ($this->attachment instanceof Attachment) {
            return [];
        }

        return [
            'mime_type' => [
                'required',
                'string',
                'not_in:'.Attachment::UNKNOWN_MIME_TYPE,
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `filesize` field.
     *
     * @return array
     */
    protected function filesizeRules(): array
    {
        if ($this->attachment instanceof Attachment) {
            return [];
        }

        return [
            'filesize' => [
                'required',
                'integer',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `properties` field.
     *
     * @return array
     */
    protected function propertiesRules(): array
    {
        return [
            'properties' => [
                'sometimes',
                'array',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `downloads` field.
     *
     * @return array
     */
    protected function downloadsRules(): array
    {
        return [
            'downloads' => [
                'nullable',
                'integer',
            ],
        ];
    }
}
