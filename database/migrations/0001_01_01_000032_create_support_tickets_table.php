<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('number', 32)->nullable()->unique();
            $table->unsignedBigInteger('artist_id');
            $table->string('topic', 32);
            $table->string('subject', 255);
            $table->text('message');
            $table->string('status', 16)->default('open');
            $table->timestamps();

            $table->foreign('artist_id')->references('id')->on('artists')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};