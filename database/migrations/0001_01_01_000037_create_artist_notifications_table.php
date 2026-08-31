<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('artist_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('artist_id');
            $table->string('type', 32)->default('ticket_reply'); // ticket_reply|...
            $table->string('title');
            $table->text('body')->nullable();
            $table->string('url')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->foreign('artist_id')->references('id')->on('artists')->cascadeOnDelete();
            $table->index(['artist_id', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artist_notifications');
    }
};