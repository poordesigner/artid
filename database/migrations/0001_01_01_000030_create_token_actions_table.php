<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('token_actions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('token_function_action', function (Blueprint $table) {
            $table->id();
            $table->foreignId('token_function_id')->constrained()->cascadeOnDelete();
            $table->foreignId('token_action_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['token_function_id', 'token_action_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('token_function_action');
        Schema::dropIfExists('token_actions');
    }
};