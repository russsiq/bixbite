<?php

use App\Http\Controllers\Api\V1\ArticleController;
use App\Http\Controllers\Api\V1\AtachmentController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\TagController;
use App\Http\Controllers\Api\V1\UserController;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'as' => 'api.',
], function () {
    Route::group([
        'as' => RouteServiceProvider::API_VERSION.'.',
        'prefix' => RouteServiceProvider::API_VERSION,
        'middleware' => [
            'auth:sanctum',
        ],
    ], function () {
        $resources = [
            'articles' => ArticleController::class,
            'atachments' => AtachmentController::class,
            'categories' => CategoryController::class,
            'comments' => CommentController::class,
            'tags' => TagController::class,
            'users' => UserController::class,
        ];

        Route::apiResources($resources, [
            //
        ]);
    });
});
