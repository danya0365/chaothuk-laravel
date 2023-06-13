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
        Schema::create('works', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title');
            $table->longText('description');
            $table->longText('details');
            $table->string('primary_image')->nullable();
            $table->longText('images')->nullable();
            $table->float('price')->nullable();
            $table->float('avg_review_rating')->default(0);
            $table->integer('display_priority')->default(0);
            $table->enum('work_status', WorkStatus::getValues())->default(WorkStatus::Standby());
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
        Schema::dropIfExists('works');
    }
};
