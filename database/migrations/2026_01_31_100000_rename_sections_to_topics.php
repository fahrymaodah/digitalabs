<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Rename 'sections' to 'topics' throughout the database.
     * 
     * This migration:
     * 1. Renames 'sections' table to 'topics'
     * 2. Renames 'section_id' column in 'lessons' table to 'topic_id'
     */
    public function up(): void
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Step 1: Rename the sections table to topics
        Schema::rename('sections', 'topics');

        // Step 2: Rename section_id to topic_id in lessons table
        Schema::table('lessons', function (Blueprint $table) {
            $table->renameColumn('section_id', 'topic_id');
        });

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Step 3: Add new foreign key constraint
        Schema::table('lessons', function (Blueprint $table) {
            $table->foreign('topic_id')
                ->references('id')
                ->on('topics')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Step 1: Rename topics table back to sections
        Schema::rename('topics', 'sections');

        // Step 2: Rename topic_id back to section_id
        Schema::table('lessons', function (Blueprint $table) {
            $table->renameColumn('topic_id', 'section_id');
        });

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Step 3: Add foreign key back
        Schema::table('lessons', function (Blueprint $table) {
            $table->foreign('section_id')
                ->references('id')
                ->on('sections')
                ->cascadeOnDelete();
        });
    }
};
