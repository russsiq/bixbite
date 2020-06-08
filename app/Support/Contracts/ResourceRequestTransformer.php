<?php

namespace App\Support\Contracts;

/**
 * Список публичных методов для Преобразователя данных ресурсного Запроса.
 */
interface ResourceRequestTransformer
{
    /**
     * Получить массив данных, используемых по умолчанию.
     * @return array
     */
    public function default(): array;

    /**
     * Получить массив данных для сохранения сущности.
     * @return array
     */
    public function store(): array;

    /**
     * Получить массив данных для обновления сущности.
     * @return array
     */
    public function update(): array;

    /**
     * Получить массив данных для массовго обновления сущностей.
     * @return array
     */
    public function massUpdate(): array;
}
