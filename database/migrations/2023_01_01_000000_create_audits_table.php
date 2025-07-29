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
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->timestamp('datetime')->useCurrent();
            $table->string('name')->nullable();
            $table->string('action');
            $table->string('type')->nullable();
            $table->string('model')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('staff_id')->nullable()->index();
            $table->string('title')->nullable();
            $table->text('comment')->nullable();
            $table->text('error')->nullable();
            $table->string('ip')->nullable();

            // Extra index voor het model en model_id
            $table->index(['model', 'model_id']);
        });
    }

    /**
     * Draai de migration terug.
     */
    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};
