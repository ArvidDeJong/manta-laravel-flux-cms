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
        Schema::create('iplist', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->integer('times')->default(0);
            $table->string('description')->nullable();
            $table->boolean('white')->default(false);
            $table->timestamps();
            $table->longText('data')->nullable();
        });
    }

    /**
     * Draai de migration terug.
     */
    public function down(): void
    {
        Schema::dropIfExists('iplist');
    }
};
