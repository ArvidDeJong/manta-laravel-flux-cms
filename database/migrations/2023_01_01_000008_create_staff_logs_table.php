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
        Schema::create('staff_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('staff_id')->nullable();
            $table->string('email')->nullable();
            $table->string('ip')->nullable();
            $table->longText('data')->nullable();
        });
    }

    /**
     * Draai de migration terug.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_logs');
    }
};
