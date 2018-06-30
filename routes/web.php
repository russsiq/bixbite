<?php

/**
 * @see also BBCMS\Providers\RouteServiceProvider.
 * @see also BBCMS\Http\Kernel - middleware.
 */

Route::get('/', 'HomeController@index')->name('home');
Auth::routes();
Route::fallback('NotFoundController');

// sitemap-image.xml https://support.google.com/webmasters/answer/178636?hl=ru
// sitemap.xml https://yandex.ru/support/webmaster/controlling-robot/sitemap.html

// First always.
Route::group(['prefix' => 'app_common', 'namespace' => 'Common'], function () {
    Route::get('captcha', 'CaptchaController@make')->name('captcha.url');
    Route::post('feedback', 'FeedbackController@send')->name('feedback.send')->middleware(['throttle:5,1']);
    Route::put('toggle/{model}/{id}/{attribute}', 'ToggleController@attribute')->name('toggle.attribute')->middleware(['auth', 'can:global.admin']);

    // System care.
    Route::get('clearcache/{key?}', 'SystemCareController@clearCache')->name('system_care.clearcache')->middleware(['role:owner']);
    Route::get('clearviews', 'SystemCareController@clearViews')->name('system_care.clearviews')->middleware(['role:owner']);
    Route::get('optimize', 'SystemCareController@complexOptimize')->name('system_care.optimize')->middleware(['role:owner']);
});

// Route::get('{commentable_type}/{commentable_id}/comments/{comment}', function ($postId, $commentId) {});
Route::post(
    'comments/{commentable_type}/{commentable_id}/store', 'CommentsController@store'
)->name('comment.store');

Route::get('articles', 'ArticlesController@index')->name('articles.index');
Route::post('search', 'ArticlesController@search')->name('articles.search');
Route::get('tags', 'TagsController@index')->name('tags.index');
Route::get('tags/{tag}', 'ArticlesController@tag')->name('tags.tag');

// Last always.
Route::get('{category}', 'ArticlesController@category')->name('articles.category');
Route::get('{category}/{id}-{article}.html', 'ArticlesController@article')->name('articles.article');


// // Authentication Routes...
// Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
// Route::post('login', 'Auth\LoginController@login');
// Route::post('logout', 'Auth\LoginController@logout')->name('logout');
//
// // Registration Routes...
// Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
// Route::post('register', 'Auth\RegisterController@register');
//
// // Password Reset Routes...
// Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// Route::post('password/reset', 'Auth\ResetPasswordController@reset');
