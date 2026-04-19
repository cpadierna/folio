<?php

use App\Http\Controllers\FeedController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookLogController;
use App\Http\Controllers\LibraryController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

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
    Route::get('/users/{user}', [ProfileController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/follow', [FollowController::class, 'toggle'])->name('users.follow');
});

require __DIR__.'/auth.php';
