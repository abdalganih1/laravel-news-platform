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
        Schema::create('site_info', function (Blueprint $table) {
            $table->id('info_id');
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->string('contact_phone', 50)->nullable();
            $table->string('contact_email')->nullable();
            $table->string('website_url')->nullable();
            // $table->timestamps(); // Use if you need both created_at and updated_at
            $table->timestamp('updated_at')->nullable(); // Only updated_at as per schema
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_infos');
    }
};
