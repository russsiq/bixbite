<?php

return [
    'dashboard' => env('APP_DASHBOARD', 'default'),
    'super_admins' => explode(',', env('APP_SUPER_ADMINS')),
    'theme' => env('APP_THEME', 'default'),
];
