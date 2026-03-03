<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Weekly recurring schedule
        Schema::create('work_availabilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_id')->index();
            $table->tinyInteger('day_of_week');               // 0=Sun, 1=Mon, ... 6=Sat
            $table->time('start_time');                        // e.g. 08:00
            $table->time('end_time');                          // e.g. 17:00
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            $table->foreign('work_id')->references('id')->on('works')->onDelete('cascade');
            $table->unique(['work_id', 'day_of_week', 'start_time'], 'work_avail_unique');
        });

        // Specific blocked dates (holidays, leave, etc.)
        Schema::create('work_blocked_dates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_id')->index();
            $table->date('blocked_date');
            $table->string('reason')->nullable();
            $table->timestamps();

            $table->foreign('work_id')->references('id')->on('works')->onDelete('cascade');
            $table->unique(['work_id', 'blocked_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_blocked_dates');
        Schema::dropIfExists('work_availabilities');
    }
};
