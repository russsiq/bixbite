<?php

return [
    'used' => true,
    'api_render' => 'https://www.google.com/recaptcha/api.js?render=',
    'api_verify' => 'https://www.google.com/recaptcha/api/siteverify',

    'site_key' => env('GRECAPTCHA_SITE_KEY'),
    'secret_key' => env('GRECAPTCHA_SECRET_KEY'),
    'score' => 0.5,
];
