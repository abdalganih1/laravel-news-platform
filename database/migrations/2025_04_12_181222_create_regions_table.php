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
        Schema::create('regions', function (Blueprint $table) {
            $table->id('region_id'); // Primary key: region_id
            // Foreign key relationship with governorates
            $table->foreignId('governorate_id')
                  ->constrained('governorates', 'governorate_id') // Links to governorates.governorate_id
                  ->onUpdate('cascade')
                  ->onDelete('restrict'); // Restrict deletion of governorate if regions exist
            $table->string('name', 150); // varchar(150)
            $table->string('gps_coordinates', 100)->nullable(); // varchar(100), optional
            $table->timestamps(); // created_at and updated_at

            // Indexes
            // Index on governorate_id is usually created automatically by constrained()
            // $table->index('governorate_id'); // Explicit index if needed
            $table->index(['name', 'governorate_id']); // Index for searching by name within a governorate
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};
