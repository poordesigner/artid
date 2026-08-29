<?php

namespace Database\Seeders;

use App\Models\TokenAction;
use Illuminate\Database\Seeder;

class TokenActionSeeder extends Seeder
{
    public function run(): void
    {
        $actions = [
            ['name' => 'Crear QR', 'description' => 'Genera el código QR permanente de la obra.', 'sort_order' => 1],
            ['name' => 'Crear Ficha Básica de Obra', 'description' => 'Genera la ficha pública básica de una obra.', 'sort_order' => 2],
            ['name' => 'Asociar Links de la Obra', 'description' => 'Agrega enlaces externos (video, foto, blog) a la ficha de la obra.', 'sort_order' => 3],
            ['name' => 'Crear Perfil Artista', 'description' => 'Publica el perfil del artista con declaración e información.', 'sort_order' => 4],
            ['name' => 'Asociar Exposición', 'description' => 'Registra una exposición en el historial de la obra.', 'sort_order' => 5],
            ['name' => 'Hacer Traslado de Propiedad', 'description' => 'Registra una transferencia o venta en la proveniencia de la obra.', 'sort_order' => 6],
        ];

        foreach ($actions as $action) {
            TokenAction::updateOrCreate(
                ['name' => $action['name']],
                $action
            );
        }
    }
}