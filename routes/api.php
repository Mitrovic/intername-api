<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('authenticate', [AuthController::class, 'authenticate']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
});

Route::get('books', [BookController::class, 'index']);
Route::get('books/{id}', [BookController::class, 'show']);

Route::group(['middleware' => 'auth:api'], function () {
    // CRUD operations for authenticated user
    Route::get('user/books', [BookController::class, 'index']);

    Route::post('user/books/{id}', [BookController::class, 'update']);

    Route::put('user/books/publish/{id}', [BookController::class, 'updatePublishStatus']);

    Route::delete('user/books/{id}', [BookController::class, 'destroy']);
});
