<?php

use App\Enums\BannerType;
use App\Enums\CouponAvailableType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('view_count')->default(0);
            $table->boolean('is_public')->default(1);
            $table->boolean('is_pinned')->default(0);
            $table->enum('type', BannerType::values());
            $table->string('image_url');
            $table->dateTime('expired_at')->nullable();
            $table->string('external_url')->nullable();
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
        Schema::dropIfExists('banners');
        Schema::enableForeignKeyConstraints();
    }
};
