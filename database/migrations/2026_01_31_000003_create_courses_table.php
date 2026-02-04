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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('course_categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('content')->nullable(); // Rich text content
            $table->string('thumbnail')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->string('preview_url')->nullable(); // YouTube preview
            $table->integer('total_duration')->default(0); // dalam detik (computed)
            $table->integer('total_lessons')->default(0); // computed
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->enum('access_type', ['lifetime', 'limited'])->default('lifetime');
            $table->integer('access_days')->nullable(); // jika limited
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
