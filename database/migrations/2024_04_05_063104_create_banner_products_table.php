<?php

use App\Enums\CouponExpiresType;
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
        Schema::create('banner_products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->uniqid();
            $table->string('name');
            $table->longText('detail');
            $table->longText('condition_text');
            $table->jsonb('tags');
            $table->string('image_url');
            $table->dateTime('expired_at')->nullable();
            $table->decimal('redeem_points', 12, 2);
            $table->integer('available_redeems');
            $table->integer('max_redeem_per_user')->default(1);
            $table->enum('coupon_expires_type', CouponExpiresType::values());
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
        Schema::dropIfExists('banner_products');
        Schema::enableForeignKeyConstraints();
    }
};
