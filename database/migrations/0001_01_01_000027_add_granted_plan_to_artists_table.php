<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->foreignId('granted_plan_id')->nullable()->after('is_admin')->constrained('plans')->nullOnDelete();
            $table->timestamp('granted_expires_at')->nullable()->after('granted_plan_id');
        });
    }

    public function down(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->dropConstrainedForeignId('granted_plan_id');
            $table->dropColumn('granted_expires_at');
        });
    }
};