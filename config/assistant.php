<?php

use BBCMS\Services\Assistant\BeforeInstalled;

return [
    // Логирование событий.
    'log_events' => env('ASSISTANT_LOG_EVENTS', false),

    // Настройки почтовых уведомлений о событиях.
    'mail' => [
        //
    ],

    // Настройки Архивариуса.
    'archivist' => [
        //
    ],

    // Настройки Чистильщика.
    'cleaner' => [
        //
    ],

    // Настройки Установщика.
    'installer' => [
        'requirements' => [
            'php' => '7.2.0',
            'ext-bcmath' => '*',
            'ext-ctype' => '*',
            'ext-curl' => '*',
            'ext-gd' => '*',
            'ext-json' => '1.6.0',
            'ext-mbstring' => '*',
            // 'ext-memcached' => '*',
            'ext-openssl' => '*',
            // 'ext-pcntl' => '*',
            'ext-pdo' => '*',
            // 'ext-posix' => '*',
            // 'ext-redis' => '*',
            'ext-tokenizer' => '*',
            'ext-xml' => '*',
            'ext-zip' => '1.15.2',
            'ext-zlib' => '*',

            // В качесте образца.
            'fileinfo' => function_exists('finfo_open'),

        ],

        'globals' => [
            'magic_quotes_gpc' => false,
            'magic_quotes_runtime' => false,
            'magic_quotes_sybase' => false,
            'register_globals' => false,

        ],

        'permissions' => [
            'bootstrap/cache',
            'config',
            'config/settings',
            'storage/app/backups',
            'storage/app/uploads',

        ],

        'seeds' => [
            'database' => 'DatabaseSeeder',
            'test' => 'TestContentSeeder',

        ],

        // Копирование директорий: fromDir, toDir.
        'directories' => [
            // 'fromDir' => 'toDir',

        ],

        // Создание ссылок на директории: target => link.
        'symlinks' => [
            storage_path('app/uploads') => base_path('uploads'),

        ],

        'before-installed' => BeforeInstalled::class,

    ],

    // Настройки Мастера обновлений.
    'updater' => [
        //
    ],

];
