<?php

namespace App\Http\Middleware;

use App\Contracts\JsonApiContract;
use Closure;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class JsonApiValidateMiddleware
{
    /** @var JsonApiContract */
    protected $jsonApi;

    /** @var Request */
    protected $request;

    /** @var Translator */
    protected $translator;

    /** @var ValidationFactory */
    protected $validationFactory;

    // /** @var bool */
    // protected $stopOnFirstFailure = true;

    /**
     * Create a new middleware instance.
     *
     * @param JsonApiContract  $jsonApi
     * @param Translator  $translator
     * @param ValidationFactory  $validationFactory
     */
    public function __construct(
        JsonApiContract $jsonApi,
        Translator $translator,
        ValidationFactory $validationFactory
    ) {
        $this->jsonApi = $jsonApi;
        $this->translator = $translator;
        $this->validationFactory = $validationFactory;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->setRequest($request)->validate();

        return $next($request);
    }

    /**
     * Set the Request instance.
     *
     * @param  Request  $request
     * @return $this
     */
    public function setRequest(Request $request): self
    {
        $this->jsonApi->setRequest(
            $this->request = $request
        );

        return $this;
    }

    /**
     * Run the validator's rules against its data.
     *
     * @return array
     *
     * @throws ValidationException
     */
    public function validate(): array
    {
        $validator = $this->createValidator()
            // ->stopOnFirstFailure($this->stopOnFirstFailure)
            ->after(function (ValidatorContract $validator) {
                //
            });

        if ($validator->fails()) {
            //
        }

        return $validator->validate();
    }

    /**
     * Create a new Validator instance.
     *
     * @return ValidatorContract
     */
    protected function createValidator(): ValidatorContract
    {
        return $this->validationFactory->make(
            $this->request->query(), $this->rules(),
            $this->messages(), $this->attributes()
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return array_merge(
            // Поле `include` всегда проверяется первым.
            $this->includeRules(),
            $this->fieldsRules(),
            $this->filterRules(),
            $this->sortRules(),
            $this->pageRules()
        );
    }

    /**
     * Get the validation rules used to validate `include` field.
     *
     * @return array
     */
    protected function includeRules(): array
    {
        // GET /articles?include=attachments,categories,comments.user,tags,user
        return [
            'include' => ['array'],

            'include.*' => [
                'bail', 'required', 'string', 'regex:/^[\w\_\.]{1,255}$/', // $this->validateIncludeQuery(),
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `fields` field.
     *
     * @return array
     */
    protected function fieldsRules(): array
    {
        // GET /articles?fields[articles]=title,created_at&fields[user]=name,email
        return [
            'fields' => ['array'],

            'fields.*' => ['required', 'array'],

            'fields.*.*' => [
                'bail', 'required', 'string', 'regex:/^[\w\_]{1,255}$/', //$this->validateFieldsQuery(),
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `filter` field.
     *
     * @return array
     */
    protected function filterRules(): array
    {
        // GET /articles?filter[0][field]=title&filter[0][operator]=contains&filter[0][query_1]=unde sin&filter_match=or
        return [
            'filter' => ['array'],

            'filter.*' => ['required', 'array'],

            'filter.*.field' => [
                'bail', 'required', 'string', 'regex:/^[\w\_\.]{1,255}$/', // Rule::in($this->allowedFilters()),
            ],

            'filter.*.operator' => [
                'required_with:filter.*.field', // Rule::in($this->allowedFilterOperators())
            ],

            'filter.*.query_1' => ['required_with:filter.*.field'],

            'filter.*.query_2' => ['required_if:filter.*.operator,bt,not_bt'],

            'filter_match' => ['sometimes', 'required', 'string', 'in:and,or'],
        ];
    }

    /**
     * Get the validation rules used to validate `sort` field.
     *
     * @return array
     */
    protected function sortRules(): array
    {
        // GET /articles?sort=-created_at,title
        return [
            'sort' => ['array'],

            'sort.*' => [
                'bail', 'required', 'string', 'regex:/^[-]?[\w\_\.]{1,255}$/', // $this->validateSortQuery(),
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `page` field.
     *
     * @return array
     */
    protected function pageRules(): array
    {
        // GET /articles?page[number]=1&page[size]=8
        return [
            'page' => ['array'],

            'page.number' => ['sometimes', 'integer', 'min:1'],

            'page.size' => ['required_with:page.number', 'integer', 'between:1,100'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
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
    public function attributes(): array
    {
        return [
            //
        ];
    }

    /**
     * Get allowed operators to the filter query.
     *
     * @return array
     */
    protected function allowedFilterOperators(): array
    {
        return [
            // // Boolean.
            // 'bool',

            // // Numeric.
            // 'eq',
            // 'not_eq',
            // 'lt',
            // 'gt',
            // 'lte',
            // 'gte',
            // 'bt',
            // 'not_bt',

            // // Relation count.
            // 'eq_cnt',
            // 'not_eq_cnt',
            // 'lt_cnt',
            // 'gt_cnt',

            // // String.
            // 'contains',
            // 'starts_with',
            // 'ends_with',

            // // Timestamps.
            // 'past',
            // 'next',
            // 'period',
        ];
    }
}
