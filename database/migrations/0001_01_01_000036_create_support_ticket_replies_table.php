<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('support_ticket_id');
            $table->string('sender', 16)->default('admin'); // admin|agent
            $table->longText('body');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->foreign('support_ticket_id')->references('id')->on('support_tickets')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_ticket_replies');
    }
};