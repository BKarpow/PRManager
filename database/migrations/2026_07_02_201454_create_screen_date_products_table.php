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
        Schema::create('screen_date_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('date_id');
            $table->string('path');
            $table->string('desc')->nullable();

            $table->foreign('date_id')->references('id')->on('date_products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screen_date_products');
    }
};
