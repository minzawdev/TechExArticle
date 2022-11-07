<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ArticleController;

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'articles'], function () {
    Route::post('/', [ArticleController::class, 'store']);
    Route::get('/all', [ArticleController::class, 'index']);
    Route::get('/{key}', [ArticleController::class, 'findWithDateTime']);
    Route::get('/{id}/show', [ArticleController::class, 'show']);
});
