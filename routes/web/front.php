<?php

use Illuminate\Support\Facades\Route;

/**
 * Данная группа маршрутов имеет общие в поставщике службы:
 *      - посредники: `web`.
 */

Route::get('feedback', 'FeedbackController@create')
    ->name('feedback.create');

Route::post('feedback', 'FeedbackController@send')
    ->name('feedback.send')
    ->middleware([
        'throttle:5,1',

    ]);

// Очистка кеша по ключу. Только собственник сайта.
Route::get('app_common/clearcache/{key?}', 'SystemCareController@clearCache')
    ->where('key', '.*')
    ->name('system_care.clearcache')
    ->middleware([
        'role:owner',

    ]);
