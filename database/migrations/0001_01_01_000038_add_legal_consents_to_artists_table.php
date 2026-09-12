<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->timestamp('terms_accepted_at')->nullable()->after('welcome_tokens_claimed');
            $table->string('terms_version', 20)->nullable()->after('terms_accepted_at');
            $table->string('terms_ip', 45)->nullable()->after('terms_version');
            $table->text('terms_user_agent')->nullable()->after('terms_ip');
            $table->boolean('marketing_consent')->default(false)->after('terms_user_agent');
            $table->timestamp('marketing_consent_at')->nullable()->after('marketing_consent');
            $table->string('marketing_ip', 45)->nullable()->after('marketing_consent_at');
        });

        Schema::create('legal_consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->constrained('artists')->cascadeOnDelete();
            $table->string('type', 20); // terms, marketing
            $table->string('version', 20)->nullable();
            $table->boolean('granted')->default(true);
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->index(['artist_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legal_consents');
        Schema::table('artists', function (Blueprint $table) {
            $table->dropColumn(['terms_accepted_at', 'terms_version', 'terms_ip', 'terms_user_agent', 'marketing_consent', 'marketing_consent_at', 'marketing_ip']);
        });
    }
};
