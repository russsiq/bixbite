<?php

use App\Http\Controllers\Api\V1\ArticlesController;
use App\Http\Controllers\Api\V1\AttachmentsController;
use App\Http\Controllers\Api\V1\CategoriesController;
use App\Http\Controllers\Api\V1\CommentsController;
use App\Http\Controllers\Api\V1\NotesController;
use App\Http\Controllers\Api\V1\PrivilegesController;
use App\Http\Controllers\Api\V1\SettingsController;
use App\Http\Controllers\Api\V1\TaggableController;
use App\Http\Controllers\Api\V1\TagsController;
use App\Http\Controllers\Api\V1\TemplatesController;
use App\Http\Controllers\Api\V1\UserProfileController;
use App\Http\Controllers\Api\V1\UsersController;
use App\Http\Controllers\Api\V1\XFieldsController;
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
        Route::get('settings/{module}', [SettingsController::class, 'getModule'])->name('settings.getModule');
        Route::put('settings/{module}', [SettingsController::class, 'updateModule'])->name('settings.updateModule');

        Route::put('articles', [ArticlesController::class, 'massUpdate'])->name('articles.massUpdate');
        Route::put('comments', [CommentsController::class, 'massUpdate'])->name('comments.massUpdate');

        Route::prefix('taggable')
            ->where([
                'taggable_type' => '[a-z_]+',
                'taggable_id' => '[0-9]+',
                'tag_id' => '[0-9]+',
            ])
            ->group(function () {
                Route::name('taggable.store')
                    ->post(
                        '{taggable_type}/{taggable_id}/tags/{tag_id}', [TaggableController::class, 'store']
                    );
                Route::name('taggable.destroy')
                    ->delete(
                        '{taggable_type}/{taggable_id}/tags/{tag_id}', [TaggableController::class, 'destroy']
                    );
            });

        Route::apiResources([
            'articles' => ArticlesController::class,
            'attachments' => AttachmentsController::class,
            'categories' => CategoriesController::class,
            'notes' => NotesController::class,
            'privileges' => PrivilegesController::class,
            'settings' => SettingsController::class,
            'tags' => TagsController::class,
            'templates' => TemplatesController::class,
            'x_fields' => XFieldsController::class,
        ]);

        Route::apiResource('comments', CommentsController::class)->except(['store']);
        Route::apiResource('users', UsersController::class)->except(['store']);
    });
