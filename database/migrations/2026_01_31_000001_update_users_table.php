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
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable()->change(); // nullable untuk social login
            $table->string('avatar')->nullable()->after('password');
            $table->string('phone', 20)->nullable()->after('avatar');
            $table->boolean('is_admin')->default(false)->after('phone');
            $table->string('google_id')->nullable()->unique()->after('is_admin');
            $table->string('provider')->nullable()->after('google_id'); // 'google', 'email'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable(false)->change();
            $table->dropColumn(['avatar', 'phone', 'is_admin', 'google_id', 'provider']);
        });
    }
};
