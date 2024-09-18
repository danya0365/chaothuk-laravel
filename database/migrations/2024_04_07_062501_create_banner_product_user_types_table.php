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
        Schema::create('banner_product_user_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('banner_product_id');
            $table->foreign('banner_product_id')->references('id')->on('banner_products')->cascadeOnDelete();
            $table->unsignedBigInteger('user_type_id');
            $table->foreign('user_type_id')->references('id')->on('user_types')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('banner_product_user_types');
        Schema::enableForeignKeyConstraints();
    }
};
