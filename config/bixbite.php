<?php

return [
    'super_admins' => explode(',', env('APP_SUPER_ADMINS')),
    'skin' => env('APP_SKIN', 'default'),
    'theme' => env('APP_THEME', 'default'),
];
