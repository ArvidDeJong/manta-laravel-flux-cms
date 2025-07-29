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
        Schema::create('routeseos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->integer('company_id')->nullable();
            $table->string('host')->nullable();
            $table->integer('pid')->nullable();
            $table->string('locale')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('sort')->default(1);
            $table->string('title')->nullable();
            $table->string('route')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('administration')->nullable()->comment('Administration column');
            $table->string('identifier')->nullable()->comment('Identifier column');
            $table->longText('data')->nullable();
        });
    }

    /**
     * Draai de migration terug.
     */
    public function down(): void
    {
        Schema::dropIfExists('routeseos');
    }
};
