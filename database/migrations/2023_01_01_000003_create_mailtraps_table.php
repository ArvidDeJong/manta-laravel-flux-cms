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
        Schema::create('mailtraps', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('email')->nullable();
            $table->string('event')->nullable();
            $table->string('timestamp')->nullable();
            $table->string('sending_stream')->nullable();
            $table->string('category')->nullable();
            $table->string('message_id')->nullable();
            $table->string('event_id')->nullable();
            $table->text('custom_variables')->nullable();
            $table->longText('data')->nullable();
        });
    }

    /**
     * Draai de migration terug.
     */
    public function down(): void
    {
        Schema::dropIfExists('mailtraps');
    }
};
