<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Periodos de planes:\n";
foreach (App\Models\PlanPeriod::with('plan')->get() as $p) {
    echo "  id={$p->id} plan={$p->plan?->name} period={$p->period} number={$p->number} price=\${$p->price} paddle_price={$p->paddle_price_id}\n";
}
echo "\nMax periodo id por tabla: " . App\Models\PlanPeriod::max('id') . "\n";