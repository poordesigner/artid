<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$plans = App\Models\Plan::with(['features'])->get();

foreach ($plans as $plan) {
    echo "Plan: {$plan->name} (id {$plan->id}) - activo: {$plan->is_active}\n";
    foreach ($plan->features as $feature) {
        echo "  feature: {$feature->description}\n";
    }
    echo "\n";
}