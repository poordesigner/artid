<?php

use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\CheckoutPageController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\ExhibitionController;
use App\Http\Controllers\OwnershipController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicArtworkController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WebhookController;
use App\Models\Plan;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;

Route::get('/locale/{locale}', function (string $locale) {
    $locale = in_array($locale, ['es', 'en']) ? $locale : 'es';

    Cookie::queue('locale', $locale, 60 * 24 * 365);

    return redirect()->back();
})->name('locale');

Route::get('/pay', CheckoutPageController::class)->name('checkout.page');

Route::get('/', function () {
    $plans = Plan::with(['periods', 'features'])
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    return view('welcome', compact('plans'));
});

Route::get('/o/{publicId}', [PublicArtworkController::class, 'show'])->name('public.artwork');

Route::get('/artist/{id}', [PublicArtworkController::class, 'artist'])->name('public.artist');

Route::post('/webhooks/paddle', [WebhookController::class, 'handle'])->name('webhooks.paddle');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('artworks.index');
    })->name('dashboard');

    Route::get('/ayuda', function () {
        return view('ayuda');
    })->name('ayuda');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/configuracion', [ConfigController::class, 'index'])->name('configuracion');

    Route::post('/subscribe/{period}', [SubscriptionController::class, 'checkout'])->name('subscribe.checkout');
    Route::post('/subscribe/cancel', [SubscriptionController::class, 'cancel'])->name('subscribe.cancel');
    Route::get('/subscribe/portal', [SubscriptionController::class, 'portal'])->name('subscribe.portal');

    Route::middleware('admin')->group(function () {
        Route::get('/configuracion/plans', [PlanController::class, 'index'])->name('plans.index');
        Route::post('/configuracion/plans', [PlanController::class, 'store'])->name('plans.store');
        Route::patch('/configuracion/plans/{plan}', [PlanController::class, 'update'])->name('plans.update');
        Route::delete('/configuracion/plans/{plan}', [PlanController::class, 'destroy'])->name('plans.destroy');
    });

    Route::get('/artworks', [ArtworkController::class, 'index'])->name('artworks.index');
    Route::get('/artworks/create', [ArtworkController::class, 'create'])->name('artworks.create');
    Route::post('/artworks', [ArtworkController::class, 'store'])->name('artworks.store');
    Route::get('/artworks/{artwork}', [ArtworkController::class, 'show'])->name('artworks.show');
    Route::get('/artworks/{artwork}/edit', [ArtworkController::class, 'edit'])->name('artworks.edit');
    Route::get('/artworks/{artwork}/qr', [ArtworkController::class, 'qr'])->name('artworks.qr');
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
