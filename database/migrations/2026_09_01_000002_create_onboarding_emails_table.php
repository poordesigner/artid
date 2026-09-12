<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('onboarding_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->constrained()->cascadeOnDelete();
            $table->string('step');
            $table->timestamp('sent_at');
            $table->timestamps();

            $table->unique(['artist_id', 'step']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onboarding_emails');
    }
};
