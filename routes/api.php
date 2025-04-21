<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EpisodeController;
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
Route::get('/categories/{slug}', [CategoryController::class, 'show']);

Route::get('/podcasts', [PodcastController::class, 'index']);
Route::get('/podcasts/{slug}', [PodcastController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::patch('/categories/{slug}', [CategoryController::class, 'update']);
    Route::delete('/categories/{slug}', [CategoryController::class, 'delete']);

    Route::post('/podcasts', [PodcastController::class, 'store']);
    Route::patch('/podcasts/{slug}', [PodcastController::class, 'update']);
    Route::delete('/podcasts/{slug}', [PodcastController::class, 'delete']);

    Route::get('/expisodes/newly-added', [EpisodeController::class, 'newlyAdded']);
    Route::get('/expisodes/editors-pick', [EpisodeController::class, 'editorsPick']);
    Route::get('/expisodes/trending-this-week', [EpisodeController::class, 'trendingThisWeek']);
    Route::get('/expisodes/{slug}', [EpisodeController::class, 'show']);
    Route::post('/episodes', [EpisodeController::class, 'store']);
    Route::patch('/episodes/{slug}', [EpisodeController::class, 'update']);
});
