<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Tables that need UUID for security (public-facing routes)
     */
    private array $tables = [
        'users',
        'courses', 
        'orders',
        'user_courses',
        'affiliates',
        'affiliate_payouts',
        'course_reviews',
        'coupons',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->uuid('uuid')->after('id')->nullable();
            });
        }

        // Generate UUIDs for existing records
        foreach ($this->tables as $tableName) {
            $records = DB::table($tableName)->whereNull('uuid')->get();
            foreach ($records as $record) {
                DB::table($tableName)
                    ->where('id', $record->id)
                    ->update(['uuid' => Str::uuid()->toString()]);
            }
        }

        // Now make uuid unique and not nullable
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->uuid('uuid')->nullable(false)->unique()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('uuid');
            });
        }
    }
};
