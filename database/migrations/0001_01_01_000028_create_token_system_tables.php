<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('token_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('tokens');
            $table->decimal('price_usd', 10, 2);
            $table->string('paddle_product_id')->nullable();
            $table->string('paddle_price_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('token_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['purchase', 'grant', 'consume'])->index();
            $table->integer('amount');
            $table->integer('balance_after')->default(0);
            $table->string('ref')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index('artist_id');
        });

        Schema::create('artwork_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artwork_id')->constrained()->cascadeOnDelete();
            $table->string('url');
            $table->enum('type', ['video', 'photo', 'blog']);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('artwork_id');
        });

        Schema::create('artist_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->constrained()->cascadeOnDelete();
            $table->string('url');
            $table->enum('type', ['portfolio', 'cv', 'exhibitions']);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('artist_id');
        });

        Schema::table('artists', function (Blueprint $table) {
            $table->integer('tokens_balance')->default(0)->after('is_admin');
        });
    }

    public function down(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->dropColumn('tokens_balance');
        });

        Schema::dropIfExists('artist_links');
        Schema::dropIfExists('artwork_links');
        Schema::dropIfExists('token_transactions');
        Schema::dropIfExists('token_packages');
    }
};