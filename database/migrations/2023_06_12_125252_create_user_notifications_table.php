<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\NotificationType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->morphs('notificationable', 'notificationable_morpf_key');
            $table->string('title')->nullable();
            $table->longText('details')->nullable();
            $table->string('notification_type', 100);
            $table->boolean('is_read')->default(false);
            $table->unsignedBigInteger('author_id')->unsigned()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
    }
};
