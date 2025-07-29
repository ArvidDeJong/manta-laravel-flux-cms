<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Voer de migration uit.
     */
    public function up(): void
    {
        Schema::create('manta_routes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('uri');
            $table->string('name')->nullable();
            $table->string('prefix')->nullable();
            $table->boolean('active')->default(1);
            
            // Indexes voor betere performance
            $table->index('active');
            $table->index('prefix');
            $table->index(['prefix', 'active']);
        });
    }

    /**
     * Draai de migration terug.
     */
    public function down(): void
    {
        Schema::dropIfExists('manta_routes');
    }
};
