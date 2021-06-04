<?php

namespace App\Actions\XField;

use App\Actions\ActionAbstract;
use App\Models\XField;
use Illuminate\Validation\Rule;

abstract class XFieldActionAbstract extends ActionAbstract
{
    protected ?XField $x_field = null;

    /**
     * Indicates whether validation should stop after the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    abstract protected function rules(): array;

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    protected function attributes(): array
    {
        return [
            'extensible' => $this->translate('extensible'),
            'name' => $this->translate('name'),
            'type' => $this->translate('type'),
            'params' => $this->translate('params'),
            // 'params.*.key' => ...,
            // 'params.*.value' => ...,
            'title' => $this->translate('title'),
            'description' => $this->translate('description'),
            'html_flags' => $this->translate('html_flags'),
        ];
    }

    /**
     * Get the validation rules used to validate `extensible` field.
     *
     * @return array
     */
    protected function extensibleRules(): array
    {
        return [
            'extensible' => [
                'bail',
                'required',
                'string',
                'max:255',
                Rule::in(XField::extensibles()),
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
        $limit = XField::limitLengthNameColumn();

        return [
            'name' => [
                'bail',
                'required',
                'string',
                "min:2",
                "max:{$limit}",
                'regex:/^[a-z0-9_]+$/',
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
        return [
            'type' => [
                'bail',
                'required',
                'string',
                'max:255',
                Rule::in(XField::fieldTypes()),
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `params` field.
     *
     * @return array
     */
    protected function paramsRules(): array
    {
        return [
            'params' => [
                'exclude_unless:type,array',
                'required',
                'array',
            ],

            'params.0.key' => [
                'nullable',
                'string',
                'max:255',
                'alpha_dash',
            ],

            'params.*.key' => [
                'bail',
                'required_unless:params.0.key,null',
                'string',
                'max:255',
                'alpha_dash',
            ],

            'params.*.value' => [
                'bail',
                'required',
                'string',
                'max:255',
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
                'max:500',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `html_flags` field.
     *
     * @return array
     */
    protected function htmlFlagsRules(): array
    {
        return [
            'html_flags' => [
                'nullable',
                'array',
            ],

            'html_flags.*.key' => [
                'bail',
                'required',
                'string',
                'max:255',
                'regex:/^[\w\d\_\-]+$/',
            ],

            'html_flags.*.value' => [
                'bail',
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }
}
