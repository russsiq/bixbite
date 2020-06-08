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
        return $this->request
            ->except([
                '_token',
                '_method',
                'submit',

            ]);
    }

    /**
     * Получить массив данных для сохранения сущности.
     * @return array
     */
    public function store(): array
    {
        $inputs = $this->default();

        $inputs['date_at'] = 'currdate';

        // Не доверяя пользователю,
        // выбираем его идентификатор
        // из фасада аутентификации.
        $inputs['user_id'] = Auth::id();

        return $inputs;
    }

    /**
     * Получить массив данных для обновления сущности.
     * @return array
     */
    public function update(): array
    {
        $inputs = $this->default();

        // Исключение идентификатора пользователя из списка полей ввода.
        // Таким образом, не меняем владельца записи.
        unset($inputs['user_id']);

        return $inputs;
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
}
