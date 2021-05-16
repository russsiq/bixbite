<?php

namespace App\Actions\Comment;

use App\Models\Comment;
use App\Models\Contracts\CommentableContract;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\Response as AccessResponse;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Str;
use Russsiq\DomManipulator\Facades\DOMManipulator;

abstract class CommentActionAbstract
{
    /** @var CommentableContract|null */
    protected $commentable = null;

    /** @var Comment|null */
    protected $comment = null;

    /** @var User|null */
    protected $user = null;

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
        return [
            'name' => $this->translator->get('auth.name'),
            'email' => $this->translator->get('auth.email'),
            'content' => $this->translator->get('comments.content'),
        ];
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

    /**
     * Prepare the data for validation.
     *
     * @param  array  $input
     * @return array
     */
    protected function prepareForValidation(array $input): array
    {
        $input['user_id'] = null;
        $input['is_approved'] = ! setting('comments.moderate');

        if ($this->user instanceof User) {
            $input['user_id'] = $this->user->id;
            $input['is_approved'] = $this->user->isSuperAdmin() || $this->user->id === $this->commentable->user->id;
        }

        if (empty($input['parent_id'])) {
            unset($input['parent_id']);
        }

        $input['content'] = $this->prepareContent($input['content'] ?? null);
        $input['author_name'] = Str::teaser($input['author_name'] ?? null);
        $input['author_email'] = filter_var($input['author_email'] ?? null, FILTER_SANITIZE_EMAIL, FILTER_FLAG_EMPTY_STRING_NULL);

        return $input;
    }

    /**
     * Prepare the comment content for validation.
     *
     * @param  string|null  $content
     * @return string
     */
    protected function prepareContent(?string $content): string
    {
        if (is_null($content)) {
            return (string) $content;
        }

        $content = DOMManipulator::removeEmoji($content);

        $content = DOMManipulator::wrapAsDocument($content)
            ->revisionPreTag()
            ->remove('script');

        if (! setting('comments.use_html', false)) {
            $content = Str::cleanHTML($content);
        }

        return $content;
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
     * Get the validation rules used to validate `parent_id` field.
     *
     * @return array
     */
    protected function parentIdRules(): array
    {
        return [
            'parent_id' => [
                'bail',
                'sometimes',
                'integer',
                'min:1',
                'exists:comments,id',
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
                'required',
                'string',
                'between:10,1500',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `author_name` field.
     *
     * @return array
     */
    protected function authorNameRules(): array
    {
        return [
            'author_name' => [
                'bail',
                'required_without:user_id',
                'between:3,255',
                'string',
                'unique:users,name',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `author_email` field.
     *
     * @return array
     */
    protected function authorEmailRules(): array
    {
        return [
            'author_email' => [
                'bail',
                'required_without:user_id',
                'between:6,255',
                'email',
                'unique:users,email',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `author_ip` field.
     *
     * @return array
     */
    protected function authorIpRules(): array
    {
        return [
            'author_ip' => [
                'required',
                'ip',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `is_approved` field.
     *
     * @return array
     */
    protected function isApprovedRules(): array
    {
        return [
            'is_approved' => [
                'required',
                'boolean',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `g-recaptcha-response` field.
     *
     * @return array
     */
    protected function recaptchaRules(): array
    {
        if ($this->user instanceof User) {
            return [];
        }

        if (! config('g_recaptcha.used')) {
            return [];
        }

        return [
            'g-recaptcha-response' => 'g_recaptcha',
        ];
    }
}
