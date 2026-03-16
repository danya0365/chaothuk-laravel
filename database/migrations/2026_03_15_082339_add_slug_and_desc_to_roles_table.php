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
        Schema::table('roles', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
            $table->text('desc')->nullable()->after('slug');
        });

        // Set slug for existing roles based on their name to prevent unique constraint violations
        $roles = \Illuminate\Support\Facades\DB::table('roles')->get();
        foreach ($roles as $role) {
            \Illuminate\Support\Facades\DB::table('roles')
                ->where('id', $role->id)
                ->update(['slug' => \Str::slug($role->name) ?: 'role-' . $role->id]);
        }

        Schema::table('roles', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn(['slug', 'desc']);
        });
    }
};
