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
        Schema::create('inventory_wight_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('wproduct_id');
            $table->string('value')->comment('По факту');
            $table->string('comment')->nullable();
              $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
              $table->foreign('inventory_id')->references('id')->on('inventories')->onDelete('cascade');
              $table->foreign('wproduct_id')->references('id')->on('wight_products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_wight_products');
    }
};
