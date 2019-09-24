<?php

/**
 * Данная группа маршрутов не имеет общий префикс:
 * Данная группа маршрутов имеет общие:
 *      - посредники: `web`.
 * Дополнительная информация:
 *      - BBCMS\Http\Kernel - middleware.
 *      - BBCMS\Providers\RouteServiceProvider
 */

Auth::routes();
Route::get('/', 'HomeController@index')->name('home');

// Вначале располагаем группу маршрутов, где не нужны регулярные выражения.
Route::get('feedback', 'Front\FeedbackController@create')->name('feedback.create');
Route::post('feedback', 'Front\FeedbackController@send')->name('feedback.send')->middleware(['throttle:5,1']);

// Route::get('{commentable_type}/{commentable_id}/comments/{comment}', function ($postId, $commentId) {});
Route::post(
    'comments/{commentable_type}/{commentable_id}/store', 'CommentsController@store'
)->name('comments.store');
Route::resource('comments', 'CommentsController')->only(['edit','update','destroy'])->names(['destroy' => 'comments.delete']);

Route::get('articles', 'ArticlesController@index')->name('articles.index');
Route::match(['get','post'], 'search', 'ArticlesController@search')->name('articles.search');
Route::get('tags', 'TagsController@index')->name('tags.index');
Route::get('tags/{tag}', 'ArticlesController@tag')->name('tags.tag');

Route::get('widget/{widget}', 'Front\WidgetController@provide')->where('widget', '^[a-z\.]+$');

Route::get('download/{id}', 'DownloadsController@download')->name('file.download');

Route::get('users', 'UsersController@index')->name('users.index');
Route::get('follow/{user}', 'UsersController@follow')->name('follow');
Route::get('unfollow/{user}', 'UsersController@unfollow')->name('unfollow');
Route::get('@{user}', 'UsersController@profile')->name('profile');
Route::get('profile/{user}/edit', 'UsersController@edit')->name('profile.edit')->middleware(['own_profile']);
Route::put('profile/{user}', 'UsersController@update')->name('profile.update')->middleware(['own_profile']);

// Данные маршруты всегда должны распологаться последними.
Route::get('{category}', 'ArticlesController@category')->name('articles.category');
Route::get('{category_slug}/{article_id}-{article_slug}.html', 'ArticlesController@article')->name('articles.article');

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

// Route::get('route-list', function () {
//    $exitCode = Artisan::call('route:list');
//
//    dd(Artisan::output());
// });
