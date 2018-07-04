<?php

Route::get('/', [
    'uses' => 'SystemInstall@stepСhoice',
    'as' => 'system.install.step_choice',
    'middleware' => [
        'debugbar.disable',
    ],
]);
Route::post('/', [
    'uses' => 'SystemInstall@stepСhoice',
    'as' => 'system.install.step_choice',
    'middleware' => [
        'debugbar.disable',
    ],
]);
