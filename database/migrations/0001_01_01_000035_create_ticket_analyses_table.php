<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_analyses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('support_ticket_id');
            $table->string('status', 16)->default('pending'); // pending|processing|completed|failed
            $table->text('summary')->nullable();
            $table->string('priority', 16)->nullable(); // normal|alta
            $table->longText('draft_reply')->nullable();
            $table->json('suggested_actions')->nullable();
            $table->json('analysis')->nullable();
            $table->text('error')->nullable();
            $table->string('model')->nullable();
            $table->timestamp('analyzed_at')->nullable();
            $table->timestamps();

            $table->foreign('support_ticket_id')->references('id')->on('support_tickets')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_analyses');
    }
};