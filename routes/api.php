<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\SubscriptionController;
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

Route::prefix('auth')->group(function (){
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function (){
        Route::get('user', [AuthController::class, 'user']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::prefix('author')->middleware('auth:sanctum')->group(function (){
    Route::post('register', [AuthorController::class, 'create']);
    Route::middleware('auth:sanctum')->group(function (){
        Route::prefix('subscription')->group(function (){
            Route::post('/', [SubscriptionController::class, 'create']);
        });
        Route::prefix('book')->group(function (){
            Route::post('/', [BookController::class, 'create']);
            Route::get('/moderation', [ChapterController::class, 'get_moderation']);
            Route::post('/{book}/chapter', [ChapterController::class, 'create']);
        });
    });
});


Route::prefix('public')->group(function (){
    Route::get('genre', [GenreController::class, 'index']);
    Route::prefix('author')->group(function (){
        Route::get('/', [AuthorController::class, 'index']);
        Route::prefix('/{author}')->group(function (){
            Route::get('/', [AuthorController::class, 'get']);
            Route::get('subscription', [SubscriptionController::class, 'get_author_subscriptions']);
            Route::get('book', [BookController::class, 'get_author_books']);
        });
    });
    Route::prefix('book')->group(function (){
        Route::get('/', [BookController::class, 'index']);
        Route::get('/{book}', []);
    });
});
