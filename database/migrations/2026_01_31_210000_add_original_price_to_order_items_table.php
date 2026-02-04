<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds original_price column to track the course's original price
     * at the time of purchase. This allows proper display of:
     * - Original price (course price at purchase time)
     * - Discount applied
     * - Final price paid
     */
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('original_price', 12, 2)->nullable()->after('course_id');
        });

        // Update existing records: set original_price = price (since we don't have historical data)
        // For new orders, original_price should be set from course.price
        DB::table('order_items')
            ->whereNull('original_price')
            ->update(['original_price' => DB::raw('price')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('original_price');
        });
    }
};
