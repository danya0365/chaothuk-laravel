<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\RecruitStatus;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recruits', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description');
            $table->jsonb('details')->nullable();
            $table->string('primary_image')->nullable();
            $table->jsonb('images')->nullable();
            $table->float('budget')->nullable();
            $table->integer('display_priority')->default(0);
            $table->enum('recruit_status', RecruitStatus::values())->default(RecruitStatus::STAND_BY->value);
            $table->unsignedBigInteger('province_id')->index();
            $table->unsignedBigInteger('work_type_id')->index();
            $table->unsignedBigInteger('author_id')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
            $table->foreign('work_type_id')->references('id')->on('work_types')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruits');
    }
};
