<?php

return [
    'used' => false,

    // Логирование событий.
    'log_events' => env('GRECAPTCHA_LOG_EVENTS', false),

    // Драйвер, используемый по умолчанию.
    // Поддерживаемые типы: `nullable`, `google_v3`.
    'driver' => env('GRECAPTCHA_DRIVER', 'google_v3'),

    // Настройки драйверов.
    'drivers' => [
        'nullable' => [
            'driver' => 'nullable',

        ],

        // Google reCAPTCHA v3.
        // https://developers.google.com/recaptcha/docs/v3
        'google_v3' => [
            'driver' => 'google_v3',

            // Параметры драйвера.
            'api_render' => 'https://www.google.com/recaptcha/api.js?render=',
            'api_verify' => 'https://www.google.com/recaptcha/api/siteverify',

            'site_key' => env('GRECAPTCHA_SITE_KEY'),
            'secret_key' => env('GRECAPTCHA_SECRET_KEY'),

            'score' => 0.5,

            // Настройки HTTP клиента.
            'guzzle' => [
                // 'base_uri' => 'https://www.google.com/recaptcha/',

            ],

        ],


    ],
];
