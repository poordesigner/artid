<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AiConfigController;
use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\ArtworkLinkController;
use App\Http\Controllers\ArtistLinkController;
use App\Http\Controllers\CheckoutPageController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExhibitionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OwnershipController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicArtworkController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SupportContextController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\Admin\OnboardingController;
use App\Http\Controllers\TicketAnalysisController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\TokenFunctionController;
use App\Http\Controllers\TokenPackageController;
use App\Http\Controllers\WebhookController;
use App\Models\Plan;
use App\Models\TokenPackage;
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

Route::get('/planes', function () {
    $packages = TokenPackage::active()->get();
    $tokenFunctions = \App\Models\TokenFunction::with('actions')->active()->get();

    return view('planes', compact('packages', 'tokenFunctions'));
})->name('planes');

Route::get('/o/{publicId}', [PublicArtworkController::class, 'show'])->name('public.artwork');

Route::get('/artist/{id}', [PublicArtworkController::class, 'artist'])->name('public.artist');

Route::get('/api/support/context', [SupportContextController::class, '__invoke'])->name('support.context');

Route::get('/api/support/llm', [AiConfigController::class, 'config'])->name('support.llm');

Route::get('/api/tickets/{ticket}/context', [TicketAnalysisController::class, 'context'])->name('api.tickets.context');

Route::post('/webhooks/paddle', [WebhookController::class, 'handle'])->name('webhooks.paddle');

Route::get('/ayuda', function () {
    return auth()->check()
        ? view('ayuda.panel')
        : view('ayuda');
})->name('ayuda');

Route::get('/caracteristicas', function () {
    return view('caracteristicas');
})->name('caracteristicas');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('dashboard.artist');
    })->name('dashboard');

    Route::get('/panel', [DashboardController::class, 'artist'])->name('dashboard.artist');

    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/configuracion', [ConfigController::class, 'index'])->name('configuracion');

    Route::get('/tokens', [TokenController::class, 'index'])->name('tokens.index');
    Route::post('/tokens/checkout/{package}', [TokenController::class, 'checkout'])->name('tokens.checkout');

    Route::get('/tickets', [SupportTicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/crear', [SupportTicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [SupportTicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{number}', [SupportTicketController::class, 'show'])->name('tickets.show');
    Route::get('/tickets/{number}/adjunto/{attachment}', [SupportTicketController::class, 'attachment'])->name('tickets.attachment');
    Route::post('/tickets/{number}/reply', [SupportTicketController::class, 'reply'])->name('tickets.reply');

    Route::get('/notificaciones', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notificaciones/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notificaciones/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    Route::post('/subscribe/cancel', [SubscriptionController::class, 'cancel'])->name('subscribe.cancel');
    Route::post('/subscribe/reactivate', [SubscriptionController::class, 'reactivate'])->name('subscribe.reactivate');
    Route::get('/subscribe/portal', [SubscriptionController::class, 'portal'])->name('subscribe.portal');
    Route::post('/subscribe/change/{period}', [SubscriptionController::class, 'change'])->name('subscribe.change');
    Route::post('/subscribe/{period}', [SubscriptionController::class, 'checkout'])->name('subscribe.checkout');

    Route::middleware('admin')->group(function () {
        Route::get('/configuracion/cuentas', [AccountController::class, 'index'])->name('accounts.index');
        Route::post('/configuracion/cuentas/{artist}/grant', [AccountController::class, 'grant'])->name('accounts.grant');
        Route::post('/configuracion/cuentas/{artist}/revoke', [AccountController::class, 'revoke'])->name('accounts.revoke');
        Route::post('/configuracion/cuentas/{artist}/toggle-admin', [AccountController::class, 'toggleAdmin'])->name('accounts.toggle-admin');

        Route::get('/configuracion/plans', [PlanController::class, 'index'])->name('plans.index');
        Route::post('/configuracion/plans', [PlanController::class, 'store'])->name('plans.store');
        Route::patch('/configuracion/plans/{plan}', [PlanController::class, 'update'])->name('plans.update');
        Route::delete('/configuracion/plans/{plan}', [PlanController::class, 'destroy'])->name('plans.destroy');

        Route::post('/configuracion/packages', [TokenPackageController::class, 'store'])->name('packages.store');
        Route::patch('/configuracion/packages/{package}', [TokenPackageController::class, 'update'])->name('packages.update');
        Route::delete('/configuracion/packages/{package}', [TokenPackageController::class, 'destroy'])->name('packages.destroy');

        Route::post('/configuracion/token-functions', [TokenFunctionController::class, 'store'])->name('token-functions.store');
        Route::patch('/configuracion/token-functions/{function}', [TokenFunctionController::class, 'update'])->name('token-functions.update');
        Route::delete('/configuracion/token-functions/{function}', [TokenFunctionController::class, 'destroy'])->name('token-functions.destroy');

        Route::post('/configuracion/token-actions', [TokenFunctionController::class, 'storeAction'])->name('token-actions.store');
        Route::patch('/configuracion/token-actions/{action}', [TokenFunctionController::class, 'updateAction'])->name('token-actions.update');
        Route::delete('/configuracion/token-actions/{action}', [TokenFunctionController::class, 'destroyAction'])->name('token-actions.destroy');

        Route::get('/configuracion/tickets', [SupportTicketController::class, 'adminIndex'])->name('tickets.admin');
        Route::post('/configuracion/tickets/{ticket}/status', [SupportTicketController::class, 'adminUpdateStatus'])->name('tickets.admin-status');

        Route::get('/configuracion/tickets/{ticket}', [TicketAnalysisController::class, 'show'])->name('tickets.admin-show');
        Route::post('/configuracion/tickets/{ticket}/analyze', [TicketAnalysisController::class, 'analyze'])->name('tickets.admin-analyze');
        Route::post('/configuracion/tickets/{ticket}/reply', [TicketAnalysisController::class, 'reply'])->name('tickets.admin-reply');

        Route::post('/configuracion/ai', [AiConfigController::class, 'update'])->name('ai.update');

        Route::get('/configuracion/onboarding', [OnboardingController::class, 'index'])->name('admin.onboarding');
        Route::post('/configuracion/onboarding/process', [OnboardingController::class, 'process'])->name('admin.onboarding.process');
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

    Route::post('/artworks/{artwork}/links', [ArtworkLinkController::class, 'store'])->name('artwork-links.store');
    Route::delete('/artwork-links/{link}', [ArtworkLinkController::class, 'destroy'])->name('artwork-links.destroy');

    Route::post('/profile/links', [ArtistLinkController::class, 'store'])->name('artist-links.store');
    Route::delete('/profile/links/{link}', [ArtistLinkController::class, 'destroy'])->name('artist-links.destroy');

    Route::get('/series', [SeriesController::class, 'index'])->name('series.index');
    Route::post('/series', [SeriesController::class, 'store'])->name('series.store');
    Route::patch('/series/{series}', [SeriesController::class, 'update'])->name('series.update');
    Route::delete('/series/{series}', [SeriesController::class, 'destroy'])->name('series.destroy');
});

require __DIR__.'/auth.php';
