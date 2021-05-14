<?php

namespace App\Actions\Article;

use App\Models\Article;
use App\Rules\SqlTextLength;
use App\Rules\MetaRobotsRule;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\Response as AccessResponse;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

abstract class ArticleActionAbstract
{
    /** @var Article|null */
    protected $article;

    /** @var Gate */
    protected $gate;

    /** @var Translator */
    protected $translator;

    /** @var ValidationFactory */
    protected $validationFactory;

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    abstract protected function rules(): array;

    /**
     * Create a new Action instance.
     *
     * @param Gate  $gate
     * @param Translator  $translator
     * @param ValidationFactory  $validationFactory
     */
    public function __construct(
        Gate $gate,
        Translator $translator,
        ValidationFactory $validationFactory
    ) {
        $this->gate = $gate;
        $this->translator = $translator;
        $this->validationFactory = $validationFactory;
    }

    /**
     * Authorize a given action for the current user.
     *
     * @param  string  $ability
     * @param  mixed  $arguments
     * @return AccessResponse
     *
     * @throws AuthorizationException
     */
    protected function authorize(string $ability, mixed $arguments): AccessResponse
    {
        return $this->gate->authorize($ability, $arguments);
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    protected function messages(): array
    {
        return [];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    protected function attributes(): array
    {
        return [];
    }

    /**
     * Run the validator's rules against its data.
     *
     * @param  array  $input
     * @return array
     *
     * @throws ValidationException
     */
    protected function validate(array $input): array
    {
        return $this->createValidator($input)
            ->validate();
    }

    /**
     * Create a new Validator instance.
     *
     * @param  array  $input
     * @return Validator
     */
    protected function createValidator(array $input): Validator
    {
        return $this->validationFactory->make(
            $input,
            $this->rules(),
            $this->messages(),
            $this->attributes()
        );
    }

    protected function relationshipsRules()
    {
        return [
            // Отношения, которых тут быть не должно.
            'categories' => [
                'nullable',
                'array',

            ],

            'categories.*.id' => [
                'required',
                'integer',
                'exists:categories,id',

            ],

            /*'attachments' => [
                'nullable',
                'array',
            ],

            'attachments.*' => [
                'integer',
                'exists:attachments,id',
            ],

            'images' => [
                'nullable',
                'array',
            ],

            'images.*' => [
                'integer',
                'exists:attachments,id',
            ],*/

            'tags' => [
                'nullable',
                'array',

            ],

            'tags.*.id' => [
                'required',
                'integer',
                'exists:tags,id',

            ],

            'tags.*.title' => [
                'bail',
                'required',
                'string',
                'max:255',
                'regex:/^[0-9\w\s]+$/u',
            ],

            'tags.*.slug' => [
                'bail',
                'required',
                'string',
                'max:255',
                'alpha_dash',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `x_*` fields.
     *
     * @return array
     * @todo This needs to be moved to a different location.
     */
    protected function extraFieldsRules(): array
    {
        $extensibles = [];

        foreach (Article::getModel()->x_fields as $field) {
            $rules = ['bail'];

            if (! str_contains($field->html_flags, 'required')) {
                array_push($rules, 'nullable');
            }

            switch ($field->type) {
                case 'string':
                    array_push($rules, 'string', 'max:255');
                    break;

                case 'array':
                    array_push($rules, 'string', Rule::in(
                        collect($field->params)->pluck('key')
                    ));
                    break;

                case 'text':
                    array_push($rules, 'string');
                    break;

                case 'timestamp':
                    array_push($rules, 'date');
                    break;

                default:
                    array_push($rules, $field->type);
                    break;
            }

            $extensibles[$field->name] = $rules;
        }

        return $extensibles;
    }

    /**
     * Get the validation rules used to validate `user_id` field.
     *
     * @return array
     */
    protected function userIdRules(): array
    {
        return [
            'user_id' => [
                'bail',
                'required',
                'integer',
                'min:1',
                'exists:users,id',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `image_id` field.
     *
     * @return array
     */
    protected function imageIdRules(): array
    {
        return [
            'image_id' => [
                'bail',
                'nullable',
                'integer',
                'min:1',
                'exists:attachments,id',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `state` field.
     *
     * @return array
     */
    protected function stateRules(): array
    {
        return [
            'state' => [
                'required',
                'integer',
                'in:0,1,2',
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
     * Get the validation rules used to validate `slug` field.
     *
     * @return array
     */
    protected function slugRules(): array
    {
        return [
            'slug' => [
                'bail',
                'required',
                'string',
                'max:255',
                'alpha_dash',
                with(
                    Rule::unique(Article::TABLE, 'slug'),
                    fn (Unique $unique) => $this->article instanceof Article
                        ? $unique->ignore($this->article->id, 'id')
                        : $unique
                ),
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `teaser` field.
     *
     * @return array
     */
    protected function teaserRules(): array
    {
        return [
            'teaser' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `content` field.
     *
     * @return array
     */
    protected function contentRules(): array
    {
        return [
            'content' => [
                'nullable',
                'string',
                app(SqlTextLength::class),
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `meta_description` field.
     *
     * @return array
     */
    protected function metaDescriptionRules(): array
    {
        return [
            'meta_description' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `meta_keywords` field.
     *
     * @return array
     */
    protected function metaKeywordsRules(): array
    {
        return [
            'meta_keywords' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `meta_robots` field.
     *
     * @return array
     */
    protected function metaRobotsRules(): array
    {
        return [
            'meta_robots' => app(MetaRobotsRule::class),
        ];
    }

    /**
     * Get the validation rules used to validate `on_mainpage` field.
     *
     * @return array
     */
    protected function onMainpageRules(): array
    {
        return [
            'on_mainpage' => [
                'boolean',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `is_favorite` field.
     *
     * @return array
     */
    protected function isFavoriteRules(): array
    {
        return [
            'is_favorite' => [
                'boolean',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `is_pinned` field.
     *
     * @return array
     */
    protected function isPinnedRules(): array
    {
        return [
            'is_pinned' => [
                'boolean',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `is_catpinned` field.
     *
     * @return array
     */
    protected function isCatpinnedRules(): array
    {
        return [
            'is_catpinned' => [
                'boolean',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `allow_com` field.
     *
     * @return array
     */
    protected function allowedCommentsRules(): array
    {
        return [
            'allow_com' => [
                'required',
                'numeric',
                'in:0,1,2', // {0: deny; 1: allow; 2: by default}
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `views` field.
     *
     * @return array
     */
    protected function viewsRules(): array
    {
        return [
            'views' => [
                'nullable',
                'integer',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `published_at` field.
     *
     * @return array
     */
    protected function publishedAtRules(): array
    {
        return [
            'published_at' => [
                'nullable',
                'date',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `created_at` field.
     *
     * @return array
     */
    protected function createdAtRules(): array
    {
        return [
            'created_at' => [
                'nullable',
                'date',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `updated_at` field.
     *
     * @return array
     */
    protected function updatedAtRules(): array
    {
        return [
            'updated_at' => [
                'nullable',
                'date',
            ],
        ];
    }
}
