<?php

use App\Http\Controllers\Api\V1\ArticlesController;
use App\Http\Controllers\Api\V1\CategoriesController;
use App\Http\Controllers\Api\V1\CommentsController;
use App\Http\Controllers\Api\V1\AttachmentsController;
use App\Http\Controllers\Api\V1\NotesController;
use App\Http\Controllers\Api\V1\PrivilegesController;
use App\Http\Controllers\Api\V1\SettingsController;
use App\Http\Controllers\Api\V1\TagsController;
use App\Http\Controllers\Api\V1\TemplatesController;
use App\Http\Controllers\Api\V1\UserProfileController;
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
        Route::get('profile', UserProfileController::class)->name('profile');
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
                'attachments' => AttachmentsController::class,
                'categories' => CategoriesController::class,
                'notes' => NotesController::class,
                'privileges' => PrivilegesController::class,
                'settings' => SettingsController::class,
                'templates' => TemplatesController::class,
                'x_fields' => XFieldsController::class,
            ]);

            Route::apiResource('comments', CommentsController::class)->except(['store']);
            Route::apiResource('tags', TagsController::class)->except(['store']);
            Route::apiResource('users', UsersController::class)->except(['store']);

            Route::put('articles', [ArticlesController::class, 'massUpdate'])->name('articles.massUpdate');
            Route::put('comments', [CommentsController::class, 'massUpdate'])->name('comments.massUpdate');
        });
    });
