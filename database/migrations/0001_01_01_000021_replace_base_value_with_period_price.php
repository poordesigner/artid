<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('base_value');
        });

        Schema::table('plan_periods', function (Blueprint $table) {
            $table->renameColumn('discount', 'price');
        });
    }

    public function down(): void
    {
        Schema::table('plan_periods', function (Blueprint $table) {
            $table->renameColumn('price', 'discount');
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->decimal('base_value', 10, 2);
        });
    }
};
