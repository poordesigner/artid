<?php

use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\Auth\GitHubController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

    Route::get('/auth/github/redirect', [GitHubController::class, 'redirect'])->name('auth.github.redirect');
    Route::get('/auth/github/callback', [GitHubController::class, 'callback'])->name('auth.github.callback');

    Route::get('/github', [GitHubController::class, 'settings'])->name('github.settings');
    Route::post('/github/link', [GitHubController::class, 'linkRepo'])->name('github.link');
    Route::post('/github/create', [GitHubController::class, 'createRepo'])->name('github.create');

    Route::get('/artworks', [ArtworkController::class, 'index'])->name('artworks.index');
    Route::get('/artworks/create', [ArtworkController::class, 'create'])->name('artworks.create');
    Route::post('/artworks', [ArtworkController::class, 'store'])->name('artworks.store');
    Route::get('/artworks/{artwork}/edit', [ArtworkController::class, 'edit'])->name('artworks.edit');
    Route::patch('/artworks/{artwork}', [ArtworkController::class, 'update'])->name('artworks.update');
    Route::delete('/artworks/{artwork}', [ArtworkController::class, 'destroy'])->name('artworks.destroy');
});

require __DIR__.'/auth.php';
