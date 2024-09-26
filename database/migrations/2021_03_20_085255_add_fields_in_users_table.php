<?php

use App\Enums\Theme;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_image')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('location')->nullable();
            $table->longText('biography')->nullable();
            $table->enum('theme', Theme::values())->default(Theme::DEFAULT->value);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profile_image');
            $table->dropColumn('cover_image');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('birth_date');
            $table->dropColumn('mobile_phone');
            $table->dropColumn('location');
            $table->dropColumn('biography');
            $table->dropColumn('theme');
        });
    }
};
