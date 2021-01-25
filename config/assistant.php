<?php

use App\Services\Assistant\BeforeInstalled;

return [
    // Имя маршрута для выхода из Ассистента приложения.
    'exit_route' => 'panel',

    // Логирование событий.
    'log_events' => env('ASSISTANT_LOG_EVENTS', false),

    // Настройки почтовых уведомлений о событиях.
    'mail' => [
        //
    ],

    // Настройки Архивариуса.
    'archivist' => [
        // Путь к рабочей папке, содержащей архивы приложения.
        'store_path' => env('ASSISTANT_ARCHIVIST_PATH', storage_path('app/backups')),

        // Поясняющая карта директорий относительно корня приложения `base_path`.
        'directories' => [
            // Системные файлы.
            'system' => [
                // Копируем необходимые.
                'include' => [
                    'app',
                    'bootstrap',
                    'config',
                    'database',
                    'routes',
                    'vendor',

                ],

                // Удаляем ненужные.
                'exclude' => [
                    'bootstrap/cache',
                    'config/settings',

                ],

                // Создаём необходимые `пустые` директории.
                'create' => [
                    'bootstrap/cache',
                    'storage/app',
                    'storage/app/backups',
                    'storage/framework',
                    'storage/framework/cache',
                    'storage/framework/sessions',
                    'storage/framework/views',
                    'storage/logs',

                ],

            ],

            // Активная тема.
            'theme' => [
                // Добавляем необходимые.
                'include' => [
                    'resources',
                    'public',

                ],

            ],

            // Вложенные файлы.
            'uploads' => [
                // Добавляем необходимые.
                'include' => [
                    'storage/app/public',

                ],

            ],

        ],

        // Создание ссылок на директории: target => link.
        'symlinks' => [
            storage_path('app/public') => public_path('storage'),

        ],

        // Указанные файлы расположены в корне приложения и будут добавлены в архив.
        // А другие файлы (например, `webpack.mix.js`)
        // будут исключены из списка файлов, участвующих в архивировании.
        'allowed_files' => [
            '.env.example',
            'artisan',
            'composer.json',
            'LICENSE',
            'package.json',
            'readme.md',
            'server.php',
            // 'webpack.mix.js',

        ],

        'backup' => [

        ],

        'restore' => [

        ],

        'driver' => env('ASSISTANT_ARCHIVIST_DRIVER', 'zip'),

    ],

    // Настройки Чистильщика.
    'cleaner' => [
        //
    ],

    // Настройки Установщика.
    'installer' => [
        'requirements' => [
            'php' => '7.3.0',
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
            'storage/app/public',

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
            storage_path('app/public') => public_path('storage'),

        ],

        'before-installed' => BeforeInstalled::class,

    ],

    // Настройки Мастера обновлений.
    'updater' => [
        // Путь к временной папке для загрузки обновления из репозитория.
        'download_path' => env('ASSISTANT_DOWNLOAD_PATH', storage_path('tmp')),

        // Указанные папки будут пропущены во время процесса обновления.
        'exclude_directories' => [
            'bootstrap/cache',
            'config/settings',
            'node_modules',
            'public',
            'storage',
            'vendor',

        ],

        // Указанные файлы расположены в корне приложения и будут обновлены.
        // А другие файлы (например, `webpack.mix.js`)
        // будут исключены из списка обновляемых.
        'allowed_files' => [
            '.env.example',
            'artisan',
            'composer.json',
            'LICENSE',
            'package.json',
            'readme.md',
            'server.php',
            // 'webpack.mix.js',

        ],

        // Настройки файла версионирования.
        'version_file' => [
            // Имя файла версионирования со сведениями о релизе.
            'filename' => 'assistant-new-version',

            // Время хранения файла версионирования в секундах.
            // По факту частота проверки наличия обновлений.
            'store_time' => 24 * 60 * 60,

        ],

        // Формат строки имени архива, описывающий версию приложения.
        // Например, имя вложения: `app_name-v1.0.0.zip`.
        'version_format' => env('ASSISTANT_VERSION_FORMAT', 'bixbite-v__VERSION__.zip'),

        // Драйвер, используемый по умолчанию.
        // Поддерживаемые типы: `github`.
        'driver' => env('ASSISTANT_UPDATER_DRIVER', 'github'),

        // Настройки драйверов.
        'drivers' => [
            'github' => [
                'driver' => 'github',
                'endpoint' => 'https://api.github.com/repos/russsiq/bixbite/releases/latest',
                'version_key' => 'tag_name',
                'source_key' => 'zipball_url',
                'access_token' => env('ASSISTANT_GITHUB_ACCESS_TOKEN'),

                // Настройки HTTP клиента.
                'guzzle' => [
                    // 'base_uri' => 'https://api.github.com',
                    'headers' => [

                    ],

                ],

            ],

        ],

    ],

];
