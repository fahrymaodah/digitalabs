<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
        });

        // Generate UUID for existing lessons
        DB::table('lessons')->whereNull('uuid')->cursor()->each(function ($lesson) {
            DB::table('lessons')
                ->where('id', $lesson->id)
                ->update(['uuid' => Str::uuid()]);
        });

        // Make UUID not nullable after filling
        Schema::table('lessons', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
