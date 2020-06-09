<?php

namespace App\Http\Middleware\Transformers\Api\V1;

// Сторонние зависимости.
use App\Support\Contracts\ResourceRequestTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Преобразователь данных Запроса для Записей.
 */
class ArticlesTransformer implements ResourceRequestTransformer
{
    /**
     * Запрос для текущего ресурса.
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

        $input['title'] = filter_var($this->request->input('title'), FILTER_SANITIZE_STRING);
        $input['slug'] = string_slug($input['slug'] ?? $input['title']);
        $input['teaser'] = html_clean($input['teaser'] ?? null);

        $input['content'] = preg_replace_callback(
            "/<pre[^>]*?>(.+?)<\/pre>/is",
            function ($match) {
                return '<pre class="ql-syntax" spellcheck="false">' . html_secure($match[1]) . '</pre>';
            },
            $this->request->input('content', null)
        );
        $input['content'] = preg_replace("/\<script.*?\<\/script\>/", '', $input['content']);
        $input['content'] = $this->removeEmoji($input['content']);

        $input['description'] = teaser($input['description'] ?? null, 255);
        $input['keywords'] = teaser($input['keywords'] ?? null, 255, '');
        $input['tags'] = isset($input['tags'])
            ? array_map(
                function (string $tag) {
                    return string_slug($tag, setting('tags.delimiter', '-'), false, false);
                },
                preg_split('/,/', $input['tags'], -1, PREG_SPLIT_NO_EMPTY)
            ) : [];

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

    /**
     * Remove Emoji Characters in PHP by Himphen Hui.
     * @source https://medium.com/coding-cheatsheet/remove-emoji-characters-in-php-236034946f51
     * @param  string  $string
     * @return string
     */
    protected function removeEmoji(string $string)
    {
        // Match Emoticons.
        $regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clear_string = preg_replace($regex_emoticons, '', $string);

        // Match Miscellaneous Symbols and Pictographs.
        $regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clear_string = preg_replace($regex_symbols, '', $clear_string);

        // Match Transport And Map Symbols.
        $regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clear_string = preg_replace($regex_transport, '', $clear_string);

        // Match Miscellaneous Symbols.
        $regex_misc = '/[\x{2600}-\x{26FF}]/u';
        $clear_string = preg_replace($regex_misc, '', $clear_string);

        // Match Dingbats.
        $regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
        $clear_string = preg_replace($regex_dingbats, '', $clear_string);

        return $clear_string;
    }
}
