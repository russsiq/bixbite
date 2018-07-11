<?php

/**
 * @see also BBCMS\Http\Kernel - middleware.
 * @see also BBCMS\Providers\RouteServiceProvider.
 */

Auth::routes();
Route::fallback('NotFoundController');
Route::get('/', 'HomeController@index')->name('home');

// Sitemap controllers. https://www.sitemaps.org/protocol.html.
Route::group(['namespace' => 'Common'], function () {
    Route::get('sitemap.xml', 'SitemapController@index')->name('sitemap.xml');
    Route::get('sitemap/home.xml', 'SitemapController@home')->name('sitemap.home.xml');
    Route::get('sitemap/articles.xml', 'SitemapController@articles')->name('sitemap.articles.xml');
    Route::get('sitemap/categories.xml', 'SitemapController@categories')->name('sitemap.categories.xml');
});

// First always.
Route::group(['prefix' => 'app_common', 'namespace' => 'Common'], function () {
    Route::get('captcha', 'CaptchaController@make')->name('captcha.url');
    Route::post('feedback', 'FeedbackController@send')->name('feedback.send')->middleware(['throttle:5,1']);
    Route::put('toggle/{model}/{id}/{attribute}', 'ToggleController@attribute')->name('toggle.attribute')->middleware(['auth', 'can:global.admin']);

    // System care. Only user with role owner.
    Route::get('clearcache/{key?}', 'SystemCareController@clearCache')->name('system_care.clearcache')->middleware(['role:owner']);
    Route::get('clearviews', 'SystemCareController@clearViews')->name('system_care.clearviews')->middleware(['role:owner']);
    Route::get('optimize', 'SystemCareController@complexOptimize')->name('system_care.optimize')->middleware(['role:owner']);
});

// Route::get('{commentable_type}/{commentable_id}/comments/{comment}', function ($postId, $commentId) {});
Route::post(
    'comments/{commentable_type}/{commentable_id}/store', 'CommentsController@store'
)->name('comments.store');
Route::resource('comments', 'CommentsController')->only(['edit','update','destroy'])->names(['destroy' => 'comments.delete']);

Route::get('articles', 'ArticlesController@index')->name('articles.index');
Route::match(['get','post'], 'search', 'ArticlesController@search')->name('articles.search');
Route::get('tags', 'TagsController@index')->name('tags.index');
Route::get('tags/{tag}', 'ArticlesController@tag')->name('tags.tag');

Route::get('users', 'UsersController@index')->name('users.index');
Route::get('profile/{user}', 'UsersController@show')->name('profile');
Route::get('profile/{user}/edit', 'UsersController@edit')->name('profile.edit')->middleware(['own_profile']);
Route::put('profile/{user}', 'UsersController@update')->name('profile.update')->middleware(['own_profile']);

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
