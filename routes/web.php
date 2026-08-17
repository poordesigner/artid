<?php

use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\Auth\GitHubController;
use App\Http\Controllers\ExhibitionController;
use App\Http\Controllers\OwnershipController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SeriesController;
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
    Route::post('/github/sync', [GitHubController::class, 'sync'])->name('github.sync');
    Route::post('/github/sync-ficha', [GitHubController::class, 'syncFicha'])->name('github.sync-ficha');

    Route::get('/artworks', [ArtworkController::class, 'index'])->name('artworks.index');
    Route::get('/artworks/create', [ArtworkController::class, 'create'])->name('artworks.create');
    Route::post('/artworks', [ArtworkController::class, 'store'])->name('artworks.store');
    Route::get('/artworks/{artwork}', [ArtworkController::class, 'show'])->name('artworks.show');
    Route::get('/artworks/{artwork}/edit', [ArtworkController::class, 'edit'])->name('artworks.edit');
    Route::get('/artworks/{artwork}/qr', [ArtworkController::class, 'qr'])->name('artworks.qr');
    Route::get('/artworks/{artwork}/image', [ArtworkController::class, 'image'])->name('artworks.image');
    Route::patch('/artworks/{artwork}', [ArtworkController::class, 'update'])->name('artworks.update');
    Route::delete('/artworks/{artwork}', [ArtworkController::class, 'destroy'])->name('artworks.destroy');

    Route::get('/artworks/{artwork}/exhibitions/create', [ExhibitionController::class, 'create'])->name('exhibitions.create');
    Route::post('/artworks/{artwork}/exhibitions', [ExhibitionController::class, 'store'])->name('exhibitions.store');
    Route::delete('/exhibitions/{exhibition}', [ExhibitionController::class, 'destroy'])->name('exhibitions.destroy');

    Route::get('/artworks/{artwork}/ownerships/create', [OwnershipController::class, 'create'])->name('ownerships.create');
    Route::post('/artworks/{artwork}/ownerships', [OwnershipController::class, 'store'])->name('ownerships.store');
    Route::post('/ownerships/{ownership}/reveal', [OwnershipController::class, 'reveal'])->name('ownerships.reveal');
    Route::delete('/ownerships/{ownership}', [OwnershipController::class, 'destroy'])->name('ownerships.destroy');

    Route::get('/series', [SeriesController::class, 'index'])->name('series.index');
    Route::post('/series', [SeriesController::class, 'store'])->name('series.store');
    Route::patch('/series/{series}', [SeriesController::class, 'update'])->name('series.update');
    Route::delete('/series/{series}', [SeriesController::class, 'destroy'])->name('series.destroy');
});

require __DIR__.'/auth.php';
