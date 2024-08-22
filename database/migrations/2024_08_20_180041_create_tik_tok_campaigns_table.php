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
        Schema::create('tiktok_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('campaign_id')->unique();
            $table->text('campaign_name');
            $table->string('primary_status');
            $table->string('account_id')->nullable();
            $table->integer('conversions')->nullable();
            $table->decimal('cost_per_conversion', 8, 2)->nullable();
            $table->decimal('cost', 8, 2)->nullable();
            $table->decimal('ctr', 8, 2)->nullable();
            $table->integer('clicks')->nullable();
            $table->decimal('cpc', 8, 2)->nullable();
            $table->decimal('frequency', 8, 2)->nullable();
            $table->string('currency')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiktok_campaigns');
    }
};
