<?php

namespace Database\Seeders;

use App\Models\Artist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Artist::factory(10)->create();

        $this->call(TokenPackageSeeder::class);
        $this->call(TokenFunctionSeeder::class);

        Artist::factory()->create([
            'name' => 'Test Artist',
            'email' => 'test@example.com',
        ]);
    }
}
