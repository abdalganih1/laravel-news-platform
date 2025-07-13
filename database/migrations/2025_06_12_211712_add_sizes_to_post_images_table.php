<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This is the recommended approach: Keep the old column and add the new one.
     */
    public function up(): void
    {
        Schema::table('post_images', function (Blueprint $table) {
            // Add a new JSON column to store paths for different sizes.
            // Let 'image_url' continue to represent the original image path.
            // We change it to string for better indexing and performance.
            $table->string('image_url', 2048)->change(); // Change TEXT to VARCHAR(2048)
            $table->json('sizes')->nullable()->after('image_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_images', function (Blueprint $table) {
            $table->dropColumn('sizes');
            $table->text('image_url')->change(); // Revert back to TEXT
        });
    }
};