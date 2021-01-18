<?php

return [
    'headers' => [
        'welcome' => 'Приветствие',
        'complete' => 'Выполнено',

    ],

    'descriptions' => [
        'welcome' => '<p>Данный мастер поможет выполнить очистку, кэширование и оптимизацию приложения.</p><p>Выберите необходимые опции и нажмите <kbd>Далее</kbd>.</p>',
        'complete' => '<p>Завершена работа по выбранным вами опциям:</p>',

    ],

    'strings' => [

    ],

    'forms' => [
        'legends' => [
            'clear' => 'Очистка',
            'cache' => 'Кэширование',
            'optimize' => 'Оптимизация',

        ],

        'attributes' => [
            'clear_cache' => 'Кэш приложения',
            'clear_config' => 'Кэш конфигураций',
            'clear_route' => 'Кэш маршрутов',
            'clear_view' => 'Скомпилированные шаблоны',
            'clear_compiled' => 'Удалить скомпилированные файлы служб и пакетов',

            'cache_config' => 'Кэшировать конфигурации',
            'cache_route' => 'Кэшировать маршруты',
            'cache_view' => 'Предварительная компиляция всех шаблонов',

            'complex_optimize' => 'Оптимизация',

        ],

        'labels' => [
            'complex_optimize' => 'Выполнить комплексную очистку и последующее кэширование',

        ],

        'placeholders' => [

        ],

        'validation' => [

        ],
    ],

    'messages' => [
        'errors' => [
            'isset_options' => 'Необходимо выбрать хотя бы одну опцию.',

        ],

        'success' => [
            'cache_cleared' => 'Кэш успешно очищен.',

        ],

        'Application cache cleared!' => 'Кэш приложения очищен!',
        'Compiled views cleared!' => 'Скомпилированные шаблоны удалены!',
        'Compiled services and packages files removed!' => 'Скомпилированные службы и пакеты удалены!',
        'File status cache cleared!' => 'Статус системных файлов очищен!',
        'Configuration cache cleared!' => 'Кэш конфигурационных файлов очищен!',
        'Configuration cached successfully!' => 'Кэш конфигурационных файлов успешно создан!',
        'Route cache cleared!' => 'Кэш маршрутов очищен!',
        'Routes cached successfully!' => 'Кэш маршрутов успешно создан!',
        'Blade templates cached successfully!' => 'Предварительная компиляция шаблонов успешно выполнена!',

    ],

];
