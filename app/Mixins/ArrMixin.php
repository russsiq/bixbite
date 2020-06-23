<?php

namespace App\Mixins;

class ArrMixin
{
    /**
     * Удалить пустые значения массива и объединить их разделителем.
     * @return callable
     */
    public function cluster(): callable
    {
        return function (array $array = null, string $delimiter = ' – '): string {
            if (is_null($array)) {
                return '';
            }

            return join($delimiter, array_values(array_filter($array)));
        };
    }
}
