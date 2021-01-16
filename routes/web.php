<?php

use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\DownloadsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/**
 * Данная группа маршрутов не должен иметь общий префикс в поставщике службы.
 * Данная группа маршрутов имеет общие в поставщике службы:
 *      - посредники: `web`.
 * Дополнительная информация:
 *      - App\Http\Kernel - middleware.
 *      - App\Providers\RouteServiceProvider
 *      - vendor\laravel\fortify\src\FortifyServiceProvider.php
 *      - vendor\laravel\fortify\routes\routes.php
 */

Route::get('/', [HomeController::class, 'index'])->name('home');

// Вначале располагаем группу маршрутов, где не нужны регулярные выражения.

// Route::get('{commentable_type}/{commentable_id}/comments/{comment}', function ($postId, $commentId) {});
Route::post(
    'comments/{commentable_type}/{commentable_id}/store', [CommentsController::class, 'store']
)->name('comments.store');
Route::resource('comments', CommentsController::class)->only(['edit','update','destroy'])->names(['destroy' => 'comments.delete']);

Route::get('articles', [ArticlesController::class, 'index'])->name('articles.index');
Route::match(['get','post'], 'search', [ArticlesController::class, 'search'])->name('articles.search');
Route::get('tags', [TagsController::class, 'index'])->name('tags.index');
Route::get('tags/{tag:title}', [ArticlesController::class, 'tag'])->name('tags.tag');

Route::get('downloads/{file:id}', DownloadsController::class)->name('file.download');

Route::get('users', [UsersController::class, 'index'])->name('users.index');
Route::get('follow/{user:id}', [UsersController::class, 'follow'])->name('follow');
Route::get('unfollow/{user:id}', [UsersController::class, 'unfollow'])->name('unfollow');
Route::get('@{user:id}', [UsersController::class, 'profile'])->name('profile');
Route::get('profile/{user:id}/edit', [UsersController::class, 'edit'])->name('profile.edit')->middleware(['own_profile']);
Route::put('profile/{user:id}', [UsersController::class, 'update'])->name('profile.update')->middleware(['own_profile']);

// Данные маршруты всегда должны располагаться последними.
Route::get('{category:slug}', [ArticlesController::class, 'category'])->name('articles.category');
Route::get('{category_slug}/{article_id}-{article_slug}.html', [ArticlesController::class, 'article'])->name('articles.article');
