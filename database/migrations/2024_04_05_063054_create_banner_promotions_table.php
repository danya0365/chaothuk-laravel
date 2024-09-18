<?php

use App\Enums\PromotionType;
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
        Schema::create('banner_promotions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', PromotionType::values());
            $table->string('code')->uniqid();
            $table->string('name');
            $table->longText('detail');
            $table->longText('condition_text')->nullable();
            $table->jsonb('tags');
            $table->string('image_url');
            $table->dateTime('expired_at')->nullable();
            $table->decimal('acquire_points', 12, 2)->nullable();
            $table->integer('available_missions')->nullable();
            $table->integer('max_mission_per_user')->nullable();
            $table->unsignedBigInteger('merchant_id');
            $table->foreign('merchant_id')->references('id')->on('users')->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('banner_promotions');
        Schema::enableForeignKeyConstraints();
    }
};
