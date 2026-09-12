<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artwork_id')->constrained('artworks')->cascadeOnDelete();
            $table->foreignId('artist_id')->constrained('artists')->cascadeOnDelete();
            $table->string('certificate_number', 20)->unique(); // COA-2026-000001
            $table->timestamp('issued_at');
            $table->string('issued_location', 255)->nullable();
            $table->string('holder_name', 255)->nullable();
            $table->string('holder_type', 20)->nullable(); // initial / transfer
            $table->text('custom_text')->nullable();
            $table->boolean('include_signature')->default(false);
            $table->string('hash_image', 64)->nullable();
            $table->string('hash_metadata', 64)->nullable();
            $table->text('qr_payload')->nullable();
            $table->string('pdf_path', 500)->nullable();
            $table->string('status', 20)->default('valid'); // valid, revoked
            $table->timestamp('revoked_at')->nullable();
            $table->string('terms_version', 20)->nullable();
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->index(['artwork_id', 'status']);
            $table->index('certificate_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
