<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('exhibitions', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('name');
            $table->date('end_date')->nullable()->after('start_date');
            $table->string('location')->nullable()->after('end_date');
        });

        // Preservar los datos existentes: `date` se convierte en `start_date`.
        DB::table('exhibitions')->whereNotNull('date')
            ->update(['start_date' => DB::raw('date')]);

        Schema::table('exhibitions', function (Blueprint $table) {
            $table->dropColumn('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exhibitions', function (Blueprint $table) {
            $table->date('date')->nullable()->after('links');
            $table->dropColumn(['start_date', 'end_date', 'location']);
        });

        DB::table('exhibitions')->whereNotNull('start_date')
            ->update(['date' => DB::raw('start_date')]);
    }
};
