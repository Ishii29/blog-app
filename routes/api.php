<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [UserController::class, 'register']);
    Route::post('login', [UserController::class, 'login']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::group(['middleware' => 'ability:user,admin'], function () {
            Route::get('logout', [UserController::class, 'logout']);
        });
    });
});

Route::group(['prefix' => 'user'], function () {
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::group(['middleware' => 'ability:user'], function () {
            Route::resource('posts', PostController::class);
            Route::resource('comments', CommentController::class)->except(['create', 'edit']);
            Route::get('/posts', [PostController::class, 'getPublishedPosts']);

        });
    });
});
