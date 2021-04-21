<?php


use App\Contracts\JsonApiContract;
use App\Exceptions\JsonApiException;
use Closure;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

    /** @var bool */
    // protected $stopOnFirstFailure = true;
    protected $stopOnFirstFailure = false;

    protected array $includes = [];
    protected array $fields = [];
    protected int $maxDepth = 3;

    protected array $relatedToModel = [];

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
        $this->jsonApi->setRequest(
            $this->request = $request
        );

        $this->validate($this->request->all());

        return $next($request);
    }

    protected function resource(): string
    {
        return $this->jsonApi->resourceName();
    }

    protected function parentClass(): string
    {
        return $this->jsonApi->resourceModelName();
    }

    public function attemptBuildQuery(array $input = [])
    {
        // $parent = new $this->parentClass();
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
        )->stopOnFirstFailure(
            $this->stopOnFirstFailure
        );
    }

    /**
     * Run the validator's rules against its data.
     *
     * @param  array  $input
     * @return array
     *
     * @throws ValidationException
     */
    protected function validate(array $input = []): array
    {
        $validator = $this->createValidator($input);

        $validator->after(function (Validator $validator) {
            $this->attemptBuildQuery($this->request->all());
            // if ($this->somethingElseIsInvalid()) {
            //     $validator->errors()->add('field', 'Что-то не так с этим полем!');
            // }
        });

        // if ($validator->fails()) {
        //     throw JsonApiException::makeFromValidator(
        //         $validator,
        //         'parameter',
        //         JsonResponse::HTTP_BAD_REQUEST
        //     );
        // }

        return $validator->validated();
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules(): array
    {
        // dd($this->request->all());
        return [
            // GET /articles?include=user, comments.user,tags,comments.user.atachments,comments. cadabra
            // Поле `include` всегда проверяется первым.
            // На этом основана дальнейшая проверка запрашиваемых столбцов.
            'include' => ['sometimes', 'required', 'array'],
            'include.*' => [
                'bail',
                'required',
                'string',
                'max:255',
                'regex:/^[\w\.\_]+$/',
                // $this->validateIncludeQuery(),
            ],

            // GET /articles?fields[articles]=title,body&fields[user]=name
            'fields' => ['sometimes', 'required', 'array'],
            'fields.*' => ['sometimes', 'required', 'array'],
            'fields.*.*' => [
                'bail',
                'required',
                'string',
                'max:255',
                'alpha_dash',
                $this->validateFieldsQuery(),
            ],

            // GET /articles?filter[0][column]=title&filter[0][operator]=contains&filter[0][query_1]=ipsum&filter[match]=or
            'filter' => ['sometimes', 'required', 'array'],
            'filter.match' => ['sometimes', 'required', 'in:and,or'],
            'filter.*.column' => [
                'bail',
                'required',
                'string',
                'max:255',
                'regex:/^[\w\.\_]+$/',
                'alpha_dash',
                // Rule::in($this->allowedFilters()),
            ],
            'filter.*.operator' => ['required_with:filter.*.column', Rule::in($this->allowedFilterOperators())],
            'filter.*.query_1' => ['required_with:filter.*.column'],
            'filter.*.query_2' => ['required_if:filter.*.operator,between,not_between'],

            // GET /articles?sort=-created,title,user.name
            'sort' => ['sometimes', 'required', 'array'],
            'sort.*' => [
                'bail',
                'required',
                'string',
                'max:255',
                'regex:/^[-]?[\w\.\_]+$/',
                $this->validateSortQuery(),
            ],

            // GET /articles?page[number]=1&page[size]=8
            'page.number' => ['sometimes', 'integer', 'min:1'],
            'page.size' => ['required_with:page.number', 'integer', 'between:1,100'],
        ];
    }

    protected function validateFieldsQuery(): callable
    {
        return function (string $attribute, mixed $value, callable $message)
        {
            $resource = $this->getResourseFromFieldsName($attribute);

            // Как убедиться, что у модели есть эти поля ???

            if ($resource !== $this->resource()) {
                $this->ensureIsValidRequestedRelation(
                    $this->parentClass(), $resource
                );

                // dump($this->relatedToModel[$resource]);
            }


        };
    }

    protected function getResourseFromFieldsName($attribute): string
    {
        preg_match('/^fields\.(?P<resource>\w+)\.(?P<key>\d+)$/', $attribute, $matches);

        if (empty($resource = $matches['resource'])) {
            throw JsonApiException::make(JsonResponse::HTTP_BAD_REQUEST, [
                [
                    'detail' => sprintf(
                        'The requested field [%s] is invalid.', $attribute
                    ),
                    'source' => [
                        'parameter' => '/'.str_replace('.', '/', $attribute),
                    ],
                ]
            ]);
        }

        return $resource;
    }

    protected function validateIncludeQuery(): callable
    {
        return function (string $attribute, mixed $value, callable $message)
        {
            $this->ensureIsValidRequestedRelation(
                $this->parentClass(), $value
            );
        };
    }

    protected function validateSortQuery(): callable
    {
        return function (string $attribute, mixed $value, callable $message)
        {

        };
    }

    protected function ensureIsValidRequestedRelation(string $model, string $requestedRelation): bool
    {
        $this->relatedToModel[$model::TABLE] = $model;

        $relations = preg_split(
            '/\s*\.\s*/', $requestedRelation, flags: PREG_SPLIT_NO_EMPTY
        );

        while (count($relations) > 0) {
            $relationMethod = array_shift($relations);

            if (method_exists($model, $relationMethod)) {
                $relation = (new $model)->{$relationMethod}();

                if ($relation instanceof Relation) {

                    $model = get_class($relation->getRelated());

                    $this->relatedToModel[$relationMethod] = $model;

                    if (empty($relations)) {
                        return true;
                    }

                    continue;
                }
            }

            break;
        }

        throw JsonApiException::make(JsonResponse::HTTP_BAD_REQUEST, [
            [
                'detail' => sprintf(
                    'Call to undefined relationship [%s] on model [%s].',
                    $relationMethod ?? $requestedRelation, $model
                ),
                'source' => [
                    'parameter' => '/'.str_replace('.', '/', $requestedRelation),
                ],
            ]
        ]);
    }

    protected function allowedFilterOperators(): array
    {
        return [
            // Boolean.
            'boolean',

            // Relation count.
            'equal_to_count',
            'not_equal_to_count',
            'less_than_count',
            'greater_than_count',

            // Count.
            'equal_to',
            'not_equal_to',
            'less_than',
            'greater_than',
            'less_than_or_equal_to',
            'greater_than_or_equal_to',
            'between',
            'not_between',

            // String.
            'contains',
            'starts_with',
            'ends_with',

            // Timestamps.
            'in_the_past',
            'in_the_next',
            'in_the_period',
        ];
    }
}

// $books = Book::with('author:id,name')->get();
// GET /articles/1?include=author,comments.author HTTP/1.1
// GET /articles/1/relationships/comments?include=comments.author HTTP/1.1
// GET /articles?include=author&fields[articles]=title,body&fields[people]=name HTTP/1.1

// protected $queryRules = [
//     'page.number' => 'filled|numeric|min:1',
//     'page.size' => 'filled|numeric|between:1,100',
// ];

// /**
//  * @return array
//  */
// protected function defaultQueryRules(): array
// {
//     return [
//         'fields' => [
//             'bail',
//             'array',
//             $this->allowedFieldSets(),
//         ],
//         'fields.*' => [
//             'filled',
//             'string',
//         ],
//         'filter' => [
//             'bail',
//             'array',
//             $this->allowedFilteringParameters(),
//         ],
//         'include' => [
//             'bail',
//             'nullable',
//             'string',
//             $this->allowedIncludePaths(),
//         ],
//         'page' => [
//             'bail',
//             'array',
//             $this->allowedPagingParameters(),
//         ],
//         'sort' => [
//             'bail',
//             'nullable',
//             'string',
//             $this->allowedSortParameters(),
//         ],
//     ];
// }

// {
//     "data": [{
//       "type": "articles",
//       "id": "1",
//       "attributes": {
//         "title": "JSON:API paints my bikeshed!"
//       },
//       "links": {
//         "self": "http://example.com/articles/1"
//       },
//       "relationships": {
//         "author": {
//           "links": {
//             "self": "http://example.com/articles/1/relationships/author",
//             "related": "http://example.com/articles/1/author"
//           },
//           "data": { "type": "people", "id": "9" }
//         },
//         "comments": {
//           "links": {
//             "self": "http://example.com/articles/1/relationships/comments",
//             "related": "http://example.com/articles/1/comments"
//           },
//           "data": [
//             { "type": "comments", "id": "5" },
//             { "type": "comments", "id": "12" }
//           ]
//         }
//       }
//     }],
//     "included": [{
//       "type": "people",
//       "id": "9",
//       "attributes": {
//         "firstName": "Dan",
//         "lastName": "Gebhardt",
//         "twitter": "dgeb"
//       },
//       "links": {
//         "self": "http://example.com/people/9"
//       }
//     }, {
//       "type": "comments",
//       "id": "5",
//       "attributes": {
//         "body": "First!"
//       },
//       "relationships": {
//         "author": {
//           "data": { "type": "people", "id": "2" }
//         }
//       },
//       "links": {
//         "self": "http://example.com/comments/5"
//       }
//     }, {
//       "type": "comments",
//       "id": "12",
//       "attributes": {
//         "body": "I like XML better"
//       },
//       "relationships": {
//         "author": {
//           "data": { "type": "people", "id": "9" }
//         }
//       },
//       "links": {
//         "self": "http://example.com/comments/12"
//       }
//     }]
//   }
