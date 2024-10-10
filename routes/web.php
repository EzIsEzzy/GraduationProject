<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobApplyController;
use App\Http\Controllers\FriendRequestController;
use App\Http\Controllers\CommentLikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserWorkDataController;

Route::group(['middleware' => 'auth'], function () {
    Route::post('workdata', [UserWorkDataController::class, 'store'])->name('workdata.store');
    Route::get('workdata', [UserWorkDataController::class, 'index'])->name('workdata.index');
    Route::put('workdata/{workDataId}', [UserWorkDataController::class, 'update'])->name('workdata.update');
    Route::delete('workdata/{workDataId}', [UserWorkDataController::class, 'destroy'])->name('workdata.destroy');
});


Route::group(['middleware' => 'auth'], function () {
    Route::post('posts/{postId}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('posts/{postId}/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::put('comments/{commentId}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('comments/{commentId}', [CommentController::class, 'destroy'])->name('comments.destroy');
});


Route::group(['middleware' => 'auth'], function () {
    Route::post('comments/{commentId}/like', [CommentLikeController::class, 'likeComment'])->name('comments.like');
    Route::get('comments/{commentId}/likes', [CommentLikeController::class, 'getCommentLikes'])->name('comments.likes');
});


Route::group(['middleware' => 'auth'], function () {
    Route::get('friend-requests', [FriendRequestController::class, 'index'])->name('friend_requests.index');
    Route::post('friend-requests/send', [FriendRequestController::class, 'sendRequest'])->name('friend_requests.send');
    Route::put('friend-requests/{id}/accept', [FriendRequestController::class, 'acceptRequest'])->name('friend_requests.accept');
    Route::delete('friend-requests/{id}/decline', [FriendRequestController::class, 'declineRequest'])->name('friend_requests.decline');
});


Route::resource('job_applies', JobApplyController::class);

Route::resource('jobs', JobController::class);

Route::resource('likes', LikeController::class);

Route::resource('posts', PostController::class);

Route::resource('users', UserController::class);
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
