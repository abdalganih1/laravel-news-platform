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
        Schema::create('favorites', function (Blueprint $table) {
            $table->id('favorite_id');
            $table->foreignId('user_id')
                  ->constrained('users', 'user_id') // Adjust if user PK is 'id'
                  ->onUpdate('cascade')
                  ->onDelete('cascade'); // Delete favorite if user deleted
            $table->foreignId('post_id')
                  ->constrained('posts', 'post_id')
                  ->onUpdate('cascade')
                  ->onDelete('cascade'); // Delete favorite if post deleted

            // $table->timestamps(); // Use if you need both created_at and updated_at
            $table->timestamp('created_at')->useCurrent(); // Only created_at needed

            // Unique constraint: a user can favorite a post only once
            $table->unique(['user_id', 'post_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
