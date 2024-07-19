<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogPostController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->middleware("verify_token")->group(function() {

    Route::post('/blogs', [BlogController::class, 'create']);
    Route::get('/blogs', [BlogController::class, 'getAll']);
    Route::get('/blogs/{id}', [BlogController::class, 'getById']);
    Route::put('/blogs/{id}', [BlogController::class, 'update']);
    Route::delete('/blogs/{id}', [BlogController::class, 'deleteById']);

    Route::post('/blog_posts/{blog_id}', [BlogPostController::class, 'create']);
    Route::get('/blog_posts/{blog_id}', [BlogPostController::class, 'getAllByBlogId']);
    Route::get('/blog/posts/{id}', [BlogPostController::class, 'getById']);
    Route::put('/blog/posts/{id}', [BlogPostController::class, 'update']);
    Route::delete('/blog/posts/{id}', [BlogPostController::class, 'deleteById']);
    Route::post('/blog/posts/{post_id}/{user_id}/likes', [BlogPostController::class, 'like']);
    Route::post('/blog/posts/{id}/{user_id}/comments', [BlogPostController::class, 'comment']);
    
});