<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\RecruitStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recruits', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description');
            $table->string('primary_image')->nullable();
            $table->longText('images')->nullable();
            $table->float('budget')->nullable();
            $table->integer('display_priority')->default(0);
            $table->enum('recruit_status', RecruitStatus::getValues())->default(RecruitStatus::Standby());
            $table->integer('province_id')->unsigned()->index();
            $table->integer('work_type_id')->unsigned()->index();
            $table->integer('author_id')->unsigned()->index();
            $table->timestamps();
            $table->softDeletes();
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
