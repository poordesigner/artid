<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$artist = App\Models\Artist::find(7);

// La suscripcion existe en Paddle pero no en la BD. Recrearla.
$sub = App\Models\Subscription::create([
    'artist_id' => 7,
    'plan_id' => 2,
    'plan_period_id' => 32,
    'paddle_customer_id' => 'ctm_01m0z4d075tghkt7kp0qxnw5sw',
    'paddle_subscription_id' => 'sub_01m0z4e6w984prv6zbyh1ssv5p',
    'status' => 'active',
    'current_period_start' => '2026-08-26 13:34:01',
    'current_period_end' => '2026-10-10 13:34:01',
    'next_billed_at' => '2026-10-10 13:34:01',
]);

echo "Suscripcion recreada id={$sub->id}\n";
echo "effectivePlan=" . $artist->fresh()->effectivePlan()?->name . "\n";
echo "isOnFreePlan=" . ($artist->fresh()->isOnFreePlan() ? 'si' : 'no') . "\n";

// Aplicar limites (con el fix de SQL): obras por encima del max se archivan.
$artist->fresh()->enforcePlanLimits();
echo "activeArtworksCount (despues de enforce)=" . $artist->fresh()->activeArtworksCount() . "\n";