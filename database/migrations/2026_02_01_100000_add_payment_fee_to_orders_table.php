<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('payment_fee', 12, 2)->default(0)->after('discount');
        });

        // Calculate payment_fee for existing orders
        // Formula: payment_fee = total - subtotal + discount
        DB::statement('UPDATE orders SET payment_fee = total - subtotal + discount WHERE payment_fee = 0');
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_fee');
        });
    }
};
