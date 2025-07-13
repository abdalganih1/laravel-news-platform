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
        Schema::create('claims', function (Blueprint $table) {
            $table->id('claim_id');

            // User who submitted the claim
            $table->foreignId('user_id')
                  ->constrained('users', 'user_id')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            // --- Fields for External Content ---
            // Remove the direct link to our posts table
            // $table->foreignId('post_id')->...

            // Add fields to store the reported content
            $table->string('external_url', 2048)->nullable(); // To store the link (e.g., Facebook, news site)
            $table->text('reported_text')->nullable(); // To store the text content of the claim
            $table->string('title')->nullable(); // Optional title for the claim, given by the user or editor
            // --- End of External Content Fields ---

            $table->text('user_notes')->nullable(); // User's reason/notes for the claim
            $table->string('claim_status', 20)->default('pending'); // 'pending', 'reviewed', 'cancelled'
            $table->text('admin_notes')->nullable(); // Notes from the editor/admin

            // Link to the post that was created to debunk/verify this claim
            // This is the REVERSE of the old logic.
            $table->foreignId('resolution_post_id')->nullable()
                  ->constrained('posts', 'post_id') // Link to our internal posts
                  ->onUpdate('cascade')
                  ->onDelete('set null'); // If the resolution post is deleted, don't delete the claim

            // User (admin/editor) who reviewed the claim
            $table->foreignId('reviewed_by_user_id')->nullable()
                  ->constrained('users', 'user_id')
                  ->onUpdate('cascade')
                  ->onDelete('set null');

            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps(); // created_at and updated_at

            // Indexes
            $table->index('claim_status');
            $table->index('external_url');
        });

        // New table for images attached directly to a claim
        Schema::create('claim_images', function (Blueprint $table) {
            $table->id('image_id');
            $table->foreignId('claim_id')
                  ->constrained('claims', 'claim_id')
                  ->onUpdate('cascade')
                  ->onDelete('cascade'); // Delete image if the claim is deleted
            $table->string('image_url');
            $table->string('caption')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop in reverse order of creation
        Schema::dropIfExists('claim_images');
        Schema::dropIfExists('claims');
    }
};