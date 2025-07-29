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
        Schema::create('firewalls', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes(); // Voor deleted_at
            
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            
            $table->integer('company_id')->nullable();
            $table->string('host')->nullable();
            $table->string('IP')->nullable();
            $table->string('hostname')->nullable();
            $table->string('email')->nullable();
            $table->boolean('status')->nullable();
            $table->text('comment')->nullable();
            $table->longText('data')->nullable();
        });
    }

    /**
     * Draai de migration terug.
     */
    public function down(): void
    {
        Schema::dropIfExists('firewalls');
    }
};
