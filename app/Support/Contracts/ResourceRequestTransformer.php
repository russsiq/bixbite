<?php

namespace App\Support\Contracts;

interface ResourceRequestTransformer
{
    /**
     * [default description]
     * @return array
     */
    public function default(): array;

    /**
     * [default description]
     * @return array
     */
    public function store(): array;

    /**
     * [default description]
     * @return array
     */
    public function update(): array;

    /**
     * [default description]
     * @return array
     */
    public function massUpdate(): array;
}
