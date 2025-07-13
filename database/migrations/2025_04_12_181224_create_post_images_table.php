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
        Schema::create('post_images', function (Blueprint $table) {
            $table->id('image_id');
            $table->foreignId('post_id')
                  ->constrained('posts', 'post_id')
                  ->onUpdate('cascade')
                  ->onDelete('cascade'); // Delete images if post is deleted
            $table->text('image_url'); // Store URL or path
            $table->text('caption')->nullable();
            // $table->timestamps(); // Use this if you need both created_at and updated_at
            $table->timestamp('created_at')->useCurrent(); // Only created_at as per schema
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_images');
    }
};
