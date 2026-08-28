<?php

namespace Database\Seeders;

use App\Models\TokenPackage;
use Illuminate\Database\Seeder;

class TokenPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Paquete 20',
                'description' => '20 tokens para registrar hasta 20 obras.',
                'tokens' => 20,
                'price_usd' => 20,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Paquete 50',
                'description' => '50 tokens para registrar hasta 50 obras.',
                'tokens' => 50,
                'price_usd' => 40,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Paquete 100',
                'description' => '100 tokens para registrar hasta 100 obras.',
                'tokens' => 100,
                'price_usd' => 80,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($packages as $package) {
            TokenPackage::updateOrCreate(
                ['name' => $package['name']],
                $package
            );
        }
    }
}