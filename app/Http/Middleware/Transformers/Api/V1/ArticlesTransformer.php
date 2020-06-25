<?php

namespace App\Http\Middleware\Transformers\Api\V1;

// Исключения.
use Illuminate\Validation\ValidationException;

// Зарегистрированные фасады приложения.
use Illuminate\Support\Facades\Auth;
use Russsiq\DomManipulator\Facades\DOMManipulator;

// Сторонние зависимости.
use App\Support\Contracts\ResourceRequestTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Преобразователь данных Запроса для Записей.
 */
class ArticlesTransformer implements ResourceRequestTransformer
{
    /**
     * Экземпляр Запроса.
     * @var Request
     */
    protected $request;

    /**
     * Создать новый экземпляр Преобразователя данных.
     * @param  Request  $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Получить массив данных, используемых по умолчанию.
     * @return array
     */
    public function default(): array
    {
        // NB: if isset image_id then attach image.
        $input = $this->request->except([
            '_token',
            '_method',
            'submit',

        ]);

        $input['title'] = Str::teaser($this->request->input('title'), 255, '');
        if (!setting('articles.manual_slug', false) || empty($this->request->input('slug'))) {
            $input['slug'] = Str::slug($this->request->input('title'), '-', setting('system.translite_code', 'ru__gost_2000_b'));
        }

        $input['teaser'] = Str::teaser($this->request->input('teaser'));
        $input['content'] = $this->prepareContent($this->request->input('content'));

        $input['description'] = Str::teaser($this->request->input('description'));
        $input['keywords'] = Str::teaser($this->request->input('keywords'), 255, '');

        $input['tags'] = array_map(
            function (string $tag) {
                return Str::slug($tag, setting('tags.delimiter', '-'), null);
            },
            preg_split('/,/', $this->request->input('tags'), -1, PREG_SPLIT_NO_EMPTY)
        );

        if (empty($input['date_at'])) {
            $input['updated_at'] =  date('Y-m-d H:i:s');
        } else {
            if ('currdate' === $input['date_at']) {
                $input['created_at'] = date('Y-m-d H:i:s');
            } else {
                $input['created_at'] = date_format(date_create($input['created_at']), 'Y-m-d H:i:s');
            }

            $input['updated_at'] =  null;
        }

        if (empty($input['categories']) or empty($input['state'])) {
            $input['state'] = 'unpublished';
        }

        return array_merge($input, [
            // default value to the checkbox
            'on_mainpage' => $this->request->input('on_mainpage', 0),
            'is_pinned' => $this->request->input('is_pinned', 0),
            'is_catpinned' => $this->request->input('is_catpinned', 0),
            'is_favorite' => $this->request->input('is_favorite', 0),
            'allow_com' => $this->request->input('allow_com', 2),

        ]);
    }

    /**
     * Получить массив данных для сохранения сущности.
     * @return array
     */
    public function store(): array
    {
        $this->request->merge([
            'date_at' => 'currdate',

            // Не доверяя пользователю,
            // выбираем его идентификатор
            // из фасада аутентификации.
            'user_id' => Auth::id(),
        ]);

        return $this->default();
    }

    /**
     * Получить массив данных для обновления сущности.
     * @return array
     */
    public function update(): array
    {
        // Исключение идентификатора пользователя из списка полей ввода.
        // Таким образом, не меняем владельца записи.
        $this->request->replace(
            $this->request->except('user_id')
        );

        return $this->default();
    }

    /**
     * Получить массив данных для массовго обновления сущностей.
     * @return array
     */
    public function massUpdate(): array
    {
        $inputs = $this->default();

        return $inputs;
    }

    protected function prepareContent(string $content = null): string
    {
        if (is_null($content)) {
            return '';
        }

        $content = DOMManipulator::removeEmoji($content);

        return DOMManipulator::wrapAsDocument($content)
            ->revisionPreTag()
            ->remove('script');
    }
}
