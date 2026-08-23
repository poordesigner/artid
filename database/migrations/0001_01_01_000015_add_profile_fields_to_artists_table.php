<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('name');
            $table->text('statement')->nullable()->after('avatar');
            $table->string('cv_pdf')->nullable()->after('statement');
            $table->string('website_url')->nullable()->after('cv_pdf');
            $table->string('instagram')->nullable()->after('website_url');
            $table->string('behance')->nullable()->after('instagram');
            $table->string('artstation')->nullable()->after('behance');
            $table->string('youtube')->nullable()->after('artstation');
            $table->string('tiktok')->nullable()->after('youtube');
        });
    }

    public function down(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->dropColumn([
                'avatar', 'statement', 'cv_pdf', 'website_url',
                'instagram', 'behance', 'artstation', 'youtube', 'tiktok',
            ]);
        });
    }
};
