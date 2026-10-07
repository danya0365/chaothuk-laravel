<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->rebuildParentId(nullable: true);
    }

    public function down(): void
    {
        $this->rebuildParentId(nullable: false);
    }

    /**
     * Drop and re-add posts.parent_id with the wanted nullability.
     *
     * The index has to go before the column: MySQL keeps the index when the column
     * drops and then complains about the leftover name, while SQLite refuses the
     * drop outright.
     */
    private function rebuildParentId(bool $nullable): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropIndex(['parent_id']);
            $table->dropColumn('parent_id');
        });

        Schema::table('posts', function (Blueprint $table) use ($nullable) {
            $column = $table->unsignedBigInteger('parent_id');

            if ($nullable) {
                $column->nullable();
            }

            $column->index()->after('author_id');
            $table->foreign('parent_id')->references('id')->on('posts')->onDelete('cascade');
        });
    }
};
