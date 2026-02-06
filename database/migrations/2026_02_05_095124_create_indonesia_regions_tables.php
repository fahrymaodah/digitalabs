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
        // Provinces
        Schema::create('provinces', function (Blueprint $table) {
            $table->string('id', 4)->primary();
            $table->string('name');
            $table->timestamps();
            
            $table->index('name');
        });

        // Cities/Regencies
        Schema::create('cities', function (Blueprint $table) {
            $table->string('id', 6)->primary();
            $table->string('province_id', 4);
            $table->string('name');
            $table->timestamps();
            
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
            $table->index('name');
            $table->index('province_id');
        });

        // Districts
        Schema::create('districts', function (Blueprint $table) {
            $table->string('id', 8)->primary();
            $table->string('city_id', 6);
            $table->string('name');
            $table->timestamps();
            
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->index('name');
            $table->index('city_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('districts');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('provinces');
    }
};
