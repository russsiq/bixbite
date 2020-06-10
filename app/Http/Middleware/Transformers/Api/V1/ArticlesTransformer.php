<?php

namespace App\Http\Middleware\Transformers\Api\V1;

// Исключения.
use Illuminate\Validation\ValidationException;

// Базовые расширения PHP.
use DOMDocument;
use DOMNode;
use LibXMLError;

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
     * [ERROR_HTML_UNKNOWN_TAG description]
     * @const integer
     */
    const ERROR_HTML_UNKNOWN_TAG = 801;

    /**
     * [PRE_TRUSTED_CLASS description]
     * @const string
     */
    const PRE_TRUSTED_CLASS = 'ql-syntax';

    /**
     * [HTML_TRUSTED_TAGS description]
     * @const string[]
     */
    const HTML_TRUSTED_TAGS = [
        'audio',
        'canvas',
        'details',
        'figcaption',
        'figure',
        'mark',
        'picture',
        'section',
        'source',
        'summary',
        'video',

    ];

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
        $input['slug'] = string_slug($this->request->input('slug', $this->request->input('title')));

        $input['teaser'] = filter_var($this->request->input('teaser'), FILTER_SANITIZE_STRING);
        $input['content'] = $this->parseContent($this->request->input('content', null));

        $input['description'] = teaser($this->request->input('description') ?? null, 255);
        $input['keywords'] = teaser($this->request->input('keywords') ?? null, 255, '');

        $input['tags'] = array_map(
            function (string $tag) {
                return string_slug($tag, setting('tags.delimiter', '-'), false, false);
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

    protected function parseContent(string $content = null)
    {
        if (is_null($content)) {
            return '';
        }

        $content = $this->removeEmoji($content);

        $document = new DOMDocument('1.0', 'UTF-8');

        // Кодировка документа, как указано в объявлении XML.
        $document->encoding = 'UTF-8';

        // Форматирует вывод, добавляя отступы и дополнительные пробелы.
        $document->formatOutput = false;

        // Указание не убирать лишние пробелы и отступы. По умолчанию TRUE.
        $document->preserveWhiteSpace = true;

        libxml_use_internal_errors(true);

        $document->loadHTML(
            '<!DOCTYPE html>'
            .'<html>'
                .'<head>'
                    // .'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">'
                    .'<meta charset="utf-8">'
                .'</head>'
                .'<body>'
                    .$content
                .'</body>'
            .'</html>');

        $xmlErrors = [];

        foreach (libxml_get_errors() as $error) {
            $message = $error->message;

            if (self::ERROR_HTML_UNKNOWN_TAG === $error->code) {

                preg_match('/Tag (?<tag>\w+) invalid/i', $message, $matches);

                if (isset($matches['tag']) && in_array($matches['tag'], self::HTML_TRUSTED_TAGS)) {
                    continue;
                }
            }

            $xmlErrors[] = [
                'content' => $message,
            ];
        }

        if ($xmlErrors) {
            throw ValidationException::withMessages($xmlErrors);
        }

        libxml_clear_errors();

        array_map(function (DOMNode $pre) {
            $pre->setAttribute('class', self::PRE_TRUSTED_CLASS);
            $pre->setAttribute('spellcheck', 'false');

            $html = $pre->ownerDocument->saveHTML($pre);
            $html = preg_replace("/<pre[^>]*?>(.+?)<\/pre>/is", '$1', $html);

            $pre->nodeValue = e($html, false);
        }, iterator_to_array($document->getElementsByTagName('pre')));

        array_map(function (DOMNode $script) {
            $script->parentNode->removeChild($script);
        }, iterator_to_array($document->getElementsByTagName('script')));

        $content = $document->saveHTML(
            $document->documentElement->lastChild
        );

        $content = preg_replace("/<body>(.+?)<\/body>/is", '$1', $content);

        return $content;
    }

    /**
     * Remove Emoji Characters in PHP by Himphen Hui.
     * @source https://medium.com/coding-cheatsheet/remove-emoji-characters-in-php-236034946f51
     * @param  string  $string
     * @return string
     */
    protected function removeEmoji(string $string = null)
    {
        if (is_null($string)) {
            return '';
        }

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
