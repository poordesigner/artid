<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$artist = App\Models\Artist::find(7);
echo "Artist id=7\n";
echo "  effectivePlan=" . $artist->effectivePlan()?->name . "\n";
echo "  activeGrantedPlan=" . $artist->activeGrantedPlan()?->name . "\n";
echo "  activeSubscription:\n";
foreach ($artist->subscriptions as $s) {
    echo "    id={$s->id} plan={$s->plan?->name} period={$s->plan_period_id} status={$s->status} next={$s->next_billed_at} period_end={$s->current_period_end}\n";
}
echo "  activeArtworksCount=" . $artist->activeArtworksCount() . "\n";
echo "  isOnFreePlan=" . ($artist->isOnFreePlan() ? 'si' : 'no') . "\n";