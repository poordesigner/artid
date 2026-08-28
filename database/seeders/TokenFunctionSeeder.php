<?php

namespace Database\Seeders;

use App\Models\TokenFunction;
use Illuminate\Database\Seeder;

class TokenFunctionSeeder extends Seeder
{
    public function run(): void
    {
        TokenFunction::updateOrCreate(['name' => 'Crear QR + Ficha Básica'], [
            'description' => 'Genera el QR permanente y la ficha pública básica de una obra.',
            'tokens' => 1,
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }
}