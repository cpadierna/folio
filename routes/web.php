<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserSearchController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookLogController;
use App\Http\Controllers\LibraryController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [WelcomeController::class, 'index']);

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/books/search', [BookController::class, 'search'])->name('books.search');
Route::get('/books/{googleBooksId}', [BookController::class, 'show'])->name('books.show');

Route::post('/books/{book}/log', [BookLogController::class, 'store'])->name('books.log.store');
Route::delete('/books/{book}/log', [BookLogController::class, 'destroy'])->name('books.log.destroy');

Route::get('/library', [LibraryController::class, 'index'])->name('library');
Route::get('/feed', [FeedController::class, 'index'])->middleware('auth')->name('feed');

Route::middleware('auth')->group(function () {
    Route::get('/recommendations', [RecommendationController::class, 'index'])->name('recommendations');
    Route::get('/users/search', [UserSearchController::class, 'index'])->name('users.search');
    Route::get('/users/{user}', [ProfileController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/follow', [FollowController::class, 'toggle'])->name('users.follow');
    Route::get('/book-logs/{bookLog}', [BookLogController::class, 'show'])->name('book_logs.show');
    Route::post('/book-logs/{bookLog}/like', [LikeController::class, 'toggle'])->name('book_logs.like');
    Route::post('/book-logs/{bookLog}/comments', [CommentController::class, 'store'])->name('book_logs.comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

require __DIR__.'/auth.php';
