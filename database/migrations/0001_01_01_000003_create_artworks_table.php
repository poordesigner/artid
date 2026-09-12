<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('artworks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('artwork_id')->nullable()->unique();
            $table->string('title');
            $table->string('year')->nullable();
            $table->string('edition')->nullable();
            $table->string('status')->default('created')->index();
            $table->string('series')->nullable();
            $table->string('technique')->nullable();
            $table->string('dimensions')->nullable();
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->string('owner')->nullable();
            $table->string('short_url')->nullable();
            $table->string('qr_code')->nullable();
            $table->timestamps();

            $table->index('artist_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artworks');
    }
};
