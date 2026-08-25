<?php

namespace App\Console\Commands;

use App\Models\Plan;
use App\Services\PaddleService;
use Illuminate\Console\Command;

class SyncPaddleCatalog extends Command
{
    protected $signature = 'paddle:sync';

    protected $description = 'Sincroniza los planes de ARTid con el catálogo de Paddle (productos y precios).';

    public function handle(PaddleService $paddle): int
    {
        foreach (Plan::with('periods')->get() as $plan) {
            $this->info("Plan: {$plan->name}");

            if (! $plan->paddle_product_id) {
                $product = $paddle->createProduct($plan);
                $plan->update(['paddle_product_id' => $product['id']]);
                $this->line("  producto creado: {$product['id']}");
            } else {
                $this->line("  producto existente: {$plan->paddle_product_id}");
            }

            foreach ($plan->periods as $period) {
                if (! $period->paddle_product_id) {
                    $period->update(['paddle_product_id' => $plan->paddle_product_id]);
                }

                if (! $period->paddle_price_id) {
                    $price = $paddle->createPrice($period);
                    $period->update(['paddle_price_id' => $price['id']]);
                    $this->line("  precio creado ({$period->recurrenceLabel()}): {$price['id']}");
                } else {
                    $this->line("  precio existente ({$period->recurrenceLabel()}): {$period->paddle_price_id}");
                }
            }
        }

        $this->info('Catálogo sincronizado.');

        return self::SUCCESS;
    }
}