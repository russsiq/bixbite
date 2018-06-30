<?php

Route::get('/', [
    'uses' => 'SystemInstall@stepСhoice',
    'as' => 'system.install.step_choice',
]);
Route::post('/', [
    'uses' => 'SystemInstall@stepСhoice',
    'as' => 'system.install.step_choice',
]);
