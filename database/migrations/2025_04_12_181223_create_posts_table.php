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
        Schema::create('posts', function (Blueprint $table) {
            $table->id('post_id');
            // Foreign key to users table (author/publisher)
            $table->foreignId('user_id')
                  ->constrained('users', 'user_id') // Assumes users PK is 'user_id', change if it's 'id'
                  ->onUpdate('cascade')
                  ->onDelete('restrict'); // Prevent deleting user if they have posts

            // Foreign key to regions table (optional location)
            $table->foreignId('region_id')->nullable()
                  ->constrained('regions', 'region_id')
                  ->onUpdate('cascade')
                  ->onDelete('set null'); // Allow deleting region, set post's region to null

            $table->string('title'); // Default length 255
            $table->text('text_content');
            $table->string('post_status', 30)->default('pending_verification'); // 'pending_verification', 'fake', 'real'

            // Self-referencing foreign key for correction link (optional)
            $table->foreignId('corrected_post_id')->nullable()
                  ->constrained('posts', 'post_id') // Links to another post in the same table
                  ->onUpdate('cascade')
                  ->onDelete('set null'); // Allow deleting original correction post

            $table->timestamps(); // created_at and updated_at

            // Indexes
            $table->index('post_status');
            $table->index('title');
            // user_id, region_id, corrected_post_id indexes often added by constrained()
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
