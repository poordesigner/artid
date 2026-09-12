<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->string('paddle_product_id')->nullable()->after('sort_order');
        });

        Schema::table('plan_periods', function (Blueprint $table) {
            $table->string('paddle_product_id')->nullable()->after('price');
            $table->string('paddle_price_id')->nullable()->after('paddle_product_id');
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_period_id')->constrained()->cascadeOnDelete();
            $table->string('paddle_customer_id')->nullable();
            $table->string('paddle_subscription_id')->nullable()->unique();
            $table->string('status')->default('trialing');
            $table->timestamp('next_billed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');

        Schema::table('plan_periods', function (Blueprint $table) {
            $table->dropColumn(['paddle_product_id', 'paddle_price_id']);
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('paddle_product_id');
        });
    }
};