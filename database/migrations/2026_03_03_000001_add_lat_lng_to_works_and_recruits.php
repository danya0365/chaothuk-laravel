<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('works', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('display_priority');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });

        Schema::table('recruits', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('display_priority');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });
    }

    public function down(): void
    {
        Schema::table('works', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });

        Schema::table('recruits', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
