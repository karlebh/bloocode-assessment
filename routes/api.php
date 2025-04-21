<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PodcastController;
use App\Http\Middleware\APIGuest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register',  [AuthController::class, 'register']);
Route::post('/logout',  [AuthController::class, 'logout'])->middleware(APIGuest::class);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

Route::get('/podcasts', [PodcastController::class, 'index']);
Route::get('/podcasts/{id}', [PodcastController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::patch('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'delete']);

    Route::post('/podcasts', [PodcastController::class, 'store']);
    Route::patch('/podcasts/{id}', [PodcastController::class, 'update']);
    Route::delete('/podcasts/{id}', [PodcastController::class, 'delete']);
});
