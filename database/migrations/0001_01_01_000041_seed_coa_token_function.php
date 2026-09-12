<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $actionId = DB::table('token_actions')->insertGetId([
            'name' => 'Generar COA',
            'description' => 'Generar Certificado de Autenticidad para una obra',
            'is_active' => true,
            'sort_order' => 99,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $funcId = DB::table('token_functions')->insertGetId([
            'name' => 'Generar Certificado de Autenticidad (COA)',
            'description' => 'Emisión de COA bloqueado con QR firmado, hashes y nº consecutivo',
            'tokens' => 1,
            'is_active' => true,
            'sort_order' => 99,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('token_function_action')->insert([
            'token_function_id' => $funcId,
            'token_action_id' => $actionId,
        ]);
    }

    public function down(): void
    {
        $func = DB::table('token_functions')->where('name', 'Generar Certificado de Autenticidad (COA)')->first();
        if ($func) {
            DB::table('token_function_action')->where('token_function_id', $func->id)->delete();
            DB::table('token_functions')->where('id', $func->id)->delete();
        }
        DB::table('token_actions')->where('name', 'Generar COA')->delete();
    }
};
