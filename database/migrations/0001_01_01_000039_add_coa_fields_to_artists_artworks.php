<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->string('signature_image')->nullable()->after('avatar');
        });

        Schema::table('artworks', function (Blueprint $table) {
            $table->string('creation_location', 255)->nullable()->after('dimensions');
            $table->unsignedSmallInteger('edition_number')->nullable()->after('edition');
        });
    }

    public function down(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->dropColumn('signature_image');
        });
        Schema::table('artworks', function (Blueprint $table) {
            $table->dropColumn(['creation_location', 'edition_number']);
        });
    }
};
