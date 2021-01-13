<?php

namespace App\Mixins;

class LangMixin
{
    /**
     * Создаем макрос перезагрузки `json` файлов переводов.
     * Когда стронние пакеты используют помощник `trans` в своих поставщиках,
     * то метод `addJsonPath` не отрабатывает ожидаемым образом,
     * т.к. метод `load` считает, что все уже загружено:
     * https://github.com/laravel/framework/blob/6.x/src/Illuminate/Translation/Translator.php#L271
     * Пакет на котором был отслежен данный факт:
     * https://github.com/russsiq/laravel-grecaptcha/blob/master/src/app/GRecaptchaServiceProvider.php#L49.
     *
     * @return callable
     */
    public function reloadJsonPaths(): callable
    {
        return function ($namespace, $group, $locale) {
            $this->loaded[$namespace][$group][$locale] = array_merge(
                $this->loaded[$namespace][$group][$locale] ?? [],
                $this->loader->load($locale, $group, $namespace)
            );
        };
    }
}
