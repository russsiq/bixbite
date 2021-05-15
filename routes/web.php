<?php

use App\Http\Controllers\ArticleCommentController;
use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DownloadsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\UserPasswordController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

/**
 * Данная группа маршрутов не должен иметь общий префикс в поставщике службы.
 * Данная группа маршрутов имеет общие в поставщике службы:
 *      - посредники: `web`.
 * Дополнительная информация:
 *      - App\Http\Kernel - middleware.
 *      - App\Providers\RouteServiceProvider
 */

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth:sanctum', 'verified', 'password.confirm'])
    ->get('/dashboard/{any?}', DashboardController::class)
    ->where('any', '.*')
    ->name('dashboard');

Route::middleware('throttle:5,1')
    ->post('articles/{article}/comments', ArticleCommentController::class)
    ->name('articles.comments.store');

Route::resource('comments', CommentsController::class)->only(['edit','update','destroy'])->names(['destroy' => 'comments.delete']);

Route::get('articles', [ArticlesController::class, 'index'])->name('articles.index');
Route::match(['get','post'], 'search', [ArticlesController::class, 'search'])->name('articles.search');
Route::get('tags', [TagsController::class, 'index'])->name('tags.index');
Route::get('tags/{tag:slug}', [ArticlesController::class, 'tag'])->name('tags.tag');

Route::get('downloads/{attachment:id}', DownloadsController::class)->name('attachment.download');

// User, Profile, Passwords...
// @todo Make prefix `account`
Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('user/profile/{id?}', [UserProfileController::class, 'show'])->name('profile.show');
    Route::get('user/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('user/profile', [UserProfileController::class, 'update'])->name('profile.update');

    Route::put('user/password', [UserPasswordController::class, 'update'])->name('user-password.update');
});

// Данные маршруты всегда должны располагаться последними.
Route::get('{category:slug}', [ArticlesController::class, 'category'])->name('articles.category');
Route::get('{category_slug}/{article_id}-{article_slug}.html', [ArticlesController::class, 'article'])->name('articles.article');
