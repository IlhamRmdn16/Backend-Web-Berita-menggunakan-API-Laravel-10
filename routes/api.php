<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/logout', [AuthenticationController::class, 'logout']);
    Route::get('/me', [AuthenticationController::class, 'me']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::patch('/posts/{id}', [PostController::class, 'update'])->middleware('pemilik-postingan'); //cuma yang memiliki postingan yang boleh mengupdate
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->middleware('pemilik-postingan'); //cuma yang memiliki postingan yang boleh menghapus

    Route::post('/comment', [CommentController::class, 'store' ]);
    Route::patch('/comment/{id}', [CommentController::class, 'update' ])->middleware('pemilik-komentar');
    Route::delete('/comment/{id}', [CommentController::class, 'destroy' ])->middleware('pemilik-komentar');
});

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show']);

Route::post('/login', [AuthenticationController::class, 'login']);



// Route::get('/posts2/{id}', [PostController::class, 'show2']);
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
