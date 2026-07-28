<?php

use App\Http\Controllers\AdminDashboard\RoleController;
use App\Http\Controllers\AdminDashboard\UserController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Dashboard\AiController;
use App\Http\Controllers\Dashboard\NotificationController;
use App\Http\Controllers\Dashboard\PostController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send')->withoutMiddleware(['Illuminate\Foundation\Http\Middleware\VerifyCsrfToken']);

Route::get('/posts/{slug}', [App\Http\Controllers\PostController::class, 'show'])
    ->name('posts.show');

Route::get('/users/{user:username}', [ProfileController::class, 'show'])
    ->name('profile.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('/posts/{post}/favorite', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::post('/posts/{post}/bookmark', [BookmarkController::class, 'toggle'])->name('posts.bookmark');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

Route::get('/u/{username}', function () {})
    ->name('users.profile');

Route::post('users/{user}/follow', [FollowController::class, 'store'])
    ->name('users.follow')
    ->middleware(['auth:web', 'active']);
Route::delete('users/{user}/unfollow', [FollowController::class, 'destroy'])
    ->name('users.unfollow')
    ->middleware(['auth:web', 'active']);

Route::group([
    'as' => 'dashboard.',
    'prefix' => 'dashboard/',
    'middleware' => ['auth:web', 'verified', 'active'],
], function () {

    Route::put('posts/{post}/restore', [PostController::class, 'restore'])
        ->name('posts.restore');
    Route::delete('posts/{post}/force', [PostController::class, 'forceDelete'])
        ->name('posts.force-delete');
    Route::resource('posts', PostController::class);

    Route::post('ai/generate', [AiController::class, 'generate'])
        ->name('ai.generate');

    Route::group([
        'as' => 'notifications.',
        'prefix' => 'notifications/',
        'controller' => NotificationController::class,
    ], function () {
        Route::get('/', 'index')->name('index');
        Route::patch('/{id}/read', 'read')->name('read');
        Route::patch('/{id}/unread', 'unread')->name('unread');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::patch('/mark-all-read', 'markAllRead')->name('markAllRead');
        Route::patch('/mark-all-unread', 'markAllUnRead')->name('markAllUnRead');
    });
});

Route::resource('admin/users', UserController::class)
    ->middleware(['auth', 'can:users.manage']);

Route::view('/account-inactive', 'auth.inactive')->name('account.inactive');
Route::view('/account-suspended', 'auth.suspended')->name('account.suspended');

Route::resource('roles', RoleController::class)
    ->middleware(['auth', 'can:roles.manage']);
