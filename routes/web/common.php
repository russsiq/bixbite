<?php

/**
 * Данная группа маршрутов имеет общие:
 *      - префикс: `app_common`;
 *      - посредники: `web`.
 */

// System care. Only user with role owner.
Route::get('clearcache/{key?}', 'SystemCareController@clearCache')->name('system_care.clearcache')->middleware(['role:owner']);
Route::get('clearviews', 'SystemCareController@clearViews')->name('system_care.clearviews')->middleware(['role:owner']);
Route::get('optimize', 'SystemCareController@complexOptimize')->name('system_care.optimize')->middleware(['role:owner']);
