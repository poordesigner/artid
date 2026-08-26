<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

foreach (App\Models\Artist::with('subscriptions.plan')->get() as $a) {
    echo "id={$a->id} name={$a->name} email={$a->email}\n";
    echo "  granted_plan_id={$a->granted_plan_id} granted_expires_at={$a->granted_expires_at}\n";
    echo "  effectivePlan=" . ($a->effectivePlan()?->name ?? 'none') . "\n";
    echo "  activeGrantedPlan=" . ($a->activeGrantedPlan()?->name ?? 'none') . "\n";
    echo "  activeSub=" . ($a->activeSubscription()?->plan?->name ?? 'none') . "\n";
    echo "  ----------\n";
}