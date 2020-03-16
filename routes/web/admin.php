<?php

use Illuminate\Support\Facades\Route;

/**
 * Данная группа маршрутов не имеет общий префикс:
 * Данная группа маршрутов имеет общие:
 *      - посредники: `web`.
 */

Route::prefix('panel')
    ->group(function () {
        // Одностраничная административная панель.
        Route::middleware([
                'auth',
                'can:global.panel',
            ])
            ->get('/{any?}', 'PanelController')
            ->name('panel');
    });

// Route::get('/', 'DashboardController@index')->name('dashboard');
// Route::get('tags/reindex', 'DashboardController@tagsReindex')->name('admin.tags.reindex');
//
// Route::get('{module}/settings', 'SettingsController@module')->name('admin.settings.module');
// Route::post('{module}/settings', 'SettingsController@moduleUpdate')->name('admin.settings.module_save');
// Route::post('articles/mass_update', 'ArticlesController@massUpdate')->name('admin.articles.mass_update');
// Route::get('categories/position_reset', 'CategoriesController@positionReset')->name('admin.categories.position_reset'); // NEED to Formaction button //
// Route::post('categories/position_update', 'CategoriesController@positionUpdate')->name('admin.categories.position_update'); // NEED to Formaction button //
// Route::post('comments/mass_update', 'CommentsController@massUpdate')->name('admin.comments.mass_update');
// Route::post('files/upload', 'FilesController@upload')->name('admin.files.upload');
// Route::post('privileges', 'PrivilegesController@massUpdate')->name('admin.privileges.update')->middleware(['can:privileges']);
// Route::post('users/mass_update', 'UsersController@massUpdate')->name('admin.users.mass_update');
//
// Route::name('admin.')->group(function () {
//     Route::resource('articles', 'ArticlesController')->except(['show'])->names(['destroy' => 'articles.delete']);
//     Route::resource('categories', 'CategoriesController')->except(['show'])->names(['destroy' => 'categories.delete']);
//     Route::resource('comments', 'CommentsController')->only(['index','edit','update','destroy'])->names(['destroy' => 'comments.delete']);
//     Route::resource('files', 'FilesController')->names(['destroy' => 'files.delete']);
//     Route::resource('notes', 'NotesController')->names(['destroy' => 'notes.delete']);
//     Route::get('privileges', 'PrivilegesController@index')->name('privileges.index')->middleware(['can:privileges']);
//     Route::resource('settings', 'SettingsController')->except(['show'])->names(['destroy' => 'settings.delete']);
//     Route::resource('themes/templates', 'TemplatesController')->except(['create', 'show'])->names(['destroy' => 'templates.delete'])->middleware(['can:themes']);
//     Route::resource('themes', 'ThemesController')->only(['index'])->middleware(['can:themes']);
//     Route::resource('users', 'UsersController')->except(['show'])->names(['destroy' => 'users.delete']);
//     Route::resource('x_fields', 'XFieldsController')->except(['show'])->names(['destroy' => 'x_fields.delete'])->middleware(['can:x_fields']);
// });
