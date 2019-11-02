<?php

return [
    // Тип репозитория исходного кода для обновления приложения по умолчанию.
    'default' => env('SELF_UPDATER_SOURCE', 'github'),

    // Установленная версия приложения.
    'version_installed' => env('APP_VERSION'),

    // Типы репозиториев. В данный момент испольуется только `github`.
    'repository_types' => [
        'github' => [
            'type' => 'github',
            'repository_vendor' => env('SELF_UPDATER_REPO_VENDOR'),
            'repository_name' => env('SELF_UPDATER_REPO_NAME'),
            'download_path' => env('SELF_UPDATER_DOWNLOAD_PATH', storage_path('tmp')),
        ],
    ],

    // Указанные папки будут пропущены во время процесса обновления.
    'exclude_folders' => [
        'bootstrap/cache',
        'node_modules',
        'storage/app',
        'storage/framework',
        'storage/logs',
        'storage/tmp',
        'vendor',
    ],

    // Указанные файлы расположены в корне приложения и будут обновлены.
    // А другие файлы (например, `.htaccess`, `robots.txt` и т.д.)
    // будут исключены из списка во время обновления.
    'allowed_files' => [
        'composer.json',
        'LICENSE',
        'package.json',
        'readme.md',
        'webpack.mix.js',
    ],

    // Регистрация событий.
    'log_events' => env('SELF_UPDATER_LOG_EVENTS', false),

    // Настройки почтовых уведомлений о событиях.
    // В данный момент не используются.
    'mail_to' => [
        'address' => env('SELF_UPDATER_MAILTO_ADDRESS'),
        'name' => env('SELF_UPDATER_MAILTO_NAME'),
    ],

    // Регистрация пользовательских команд для Artisan.
    'artisan_commands' => [
        'pre_update' => [
            //'command:signature' => [
            //    'class' => Command class
            //    'params' => []
            //]
        ],

        'post_update' => [
            //
        ],
    ],
];
