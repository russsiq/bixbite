<?php

use App\Http\Controllers\Api\V1\ArticlesController;
use App\Http\Controllers\Api\V1\CategoriesController;
use App\Http\Controllers\Api\V1\CommentsController;
use App\Http\Controllers\Api\V1\FilesController;
use App\Http\Controllers\Api\V1\NotesController;
use App\Http\Controllers\Api\V1\PrivilegesController;
use App\Http\Controllers\Api\V1\SettingsController;
use App\Http\Controllers\Api\V1\TagsController;
use App\Http\Controllers\Api\V1\TemplatesController;
use App\Http\Controllers\Api\V1\UsersController;
use App\Http\Controllers\Api\V1\XFieldsController;
use App\Http\Middleware\TransformApiData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Данная группа маршрутов имеет общие:
 *      - префикс: `api/v1`;
 *      - посредники: `api`.
 */

Route::group([
        'middleware' => [
            'auth:sanctum',

        ],
        'as' => 'api.'

    ], function () {
        Route::get('notes/form', [NotesController::class, 'form'])->name('notes.form');
        Route::get('settings/{module}', [SettingsController::class, 'getModule'])->name('settings.getModule');
        Route::put('settings/{module}', [SettingsController::class, 'updateModule'])->name('settings.updateModule');

        Route::group([
            'middleware' => [
                TransformApiData::class,

            ],

        ], function () {
            Route::apiResources([
                'articles' => ArticlesController::class,
                'categories' => CategoriesController::class,
                'comments' => CommentsController::class,
                'files' => FilesController::class,
                'notes' => NotesController::class,
                'privileges' => PrivilegesController::class,
                'settings' => SettingsController::class,
                'tags' => TagsController::class,
                'templates' => TemplatesController::class,
                'users' => UsersController::class,
                'x_fields' => XFieldsController::class,

            ]);

            Route::put('articles', [ArticlesController::class, 'massUpdate'])->name('articles.massUpdate');
            Route::put('comments', [CommentsController::class, 'massUpdate'])->name('comments.massUpdate');
        });
    });
