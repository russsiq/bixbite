<?php

use Illuminate\Support\Facades\Route;

/**
 * Данная группа маршрутов имеет общие:
 *      - префикс: `api`;
 *      - посредники: `web`.
 */

Route::post('v1/auth/login', 'V1\AuthController@login')->name('api.auth.login');

Route::group([
        'prefix' => 'v1',
        'namespace' => 'V1',
        'middleware' => 'auth:api',
        'as' => 'api.'
    ], function () {
        Route::post('auth/logout', 'AuthController@logout')->name('auth.logout');
        Route::get('notes/form', 'NotesController@form')->name('notes.form');
        Route::get('settings/{module}', 'SettingsController@getModule')->name('settings.getModule');
        Route::put('settings/{module}', 'SettingsController@updateModule')->name('settings.updateModule');

        Route::apiResources([
            'articles' => 'ArticlesController',
            'categories' => 'CategoriesController',
            'comments' => 'CommentsController',
            'files' => 'FilesController',
            'notes' => 'NotesController',
            'privileges' => 'PrivilegesController',
            'settings' => 'SettingsController',
            // 'tags' => 'TagsController',
            'templates' => 'TemplatesController',
            'users' => 'UsersController',
            'x_fields' => 'XFieldsController',
        ]);

        Route::put('articles', 'ArticlesController@massUpdate')->name('articles.massUpdate');
        Route::put('comments', 'CommentsController@massUpdate')->name('comments.massUpdate');
    });
