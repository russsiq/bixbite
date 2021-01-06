<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/**
 * Данная группа маршрутов не должен иметь общий префикс в поставщике службы.
 * Данная группа маршрутов имеет общие в поставщике службы:
 *      - посредники: `web`.
 * Дополнительная информация:
 *      - App\Http\Kernel - middleware.
 *      - App\Providers\RouteServiceProvider
 *      - Laravel\Ui\AuthRouteMethods
 */


Auth::routes([
    // Использовать посредника middleware('password.confirm'),
    // запрашивающего подтверждение пароля от пользователя.
    'confirm' => true,

    // Разрешить регистрацию на сайте.
    'register' => true,

    // Разрешить сброс пароля.
    'reset' => true,

    // Подтверждение адреса электронной почты.
    'verify' => true,

]);

Route::get('/', 'HomeController@index')->name('home');

// Вначале располагаем группу маршрутов, где не нужны регулярные выражения.

// Route::get('{commentable_type}/{commentable_id}/comments/{comment}', function ($postId, $commentId) {});
Route::post(
    'comments/{commentable_type}/{commentable_id}/store', 'CommentsController@store'
)->name('comments.store');
Route::resource('comments', 'CommentsController')->only(['edit','update','destroy'])->names(['destroy' => 'comments.delete']);

Route::get('articles', 'ArticlesController@index')->name('articles.index');
Route::match(['get','post'], 'search', 'ArticlesController@search')->name('articles.search');
Route::get('tags', 'TagsController@index')->name('tags.index');
Route::get('tags/{tag:title}', 'ArticlesController@tag')->name('tags.tag');

Route::get('downloads/{file:id}', 'DownloadsController')->name('file.download');

Route::get('users', 'UsersController@index')->name('users.index');
Route::get('follow/{user:id}', 'UsersController@follow')->name('follow');
Route::get('unfollow/{user:id}', 'UsersController@unfollow')->name('unfollow');
Route::get('@{user:id}', 'UsersController@profile')->name('profile');
Route::get('profile/{user:id}/edit', 'UsersController@edit')->name('profile.edit')->middleware(['own_profile']);
Route::put('profile/{user:id}', 'UsersController@update')->name('profile.update')->middleware(['own_profile']);

// Данные маршруты всегда должны располагаться последними.
Route::get('{category:slug}', 'ArticlesController@category')->name('articles.category');
Route::get('{category_slug}/{article_id}-{article_slug}.html', 'ArticlesController@article')->name('articles.article');
