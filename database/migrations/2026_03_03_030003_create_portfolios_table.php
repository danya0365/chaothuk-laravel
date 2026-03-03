<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->jsonb('images')->nullable();
            $table->unsignedBigInteger('work_type_id')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('work_type_id')->references('id')->on('work_types')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
