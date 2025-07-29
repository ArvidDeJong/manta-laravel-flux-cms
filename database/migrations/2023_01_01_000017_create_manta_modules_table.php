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
        Schema::create('manta_modules', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->integer('company_id')->nullable();
            $table->string('host')->nullable();
            $table->string('locale')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('sort')->default(1);
            $table->string('name')->unique();
            $table->string('title');
            $table->json('module_name')->nullable();
            $table->string('description')->nullable();
            $table->string('tabtitle')->nullable();
            $table->string('route')->nullable();
            $table->string('url')->nullable();
            $table->string('icon')->nullable();
            $table->string('type')->nullable(); // modules, webshop, tools, dev
            $table->string('rights')->nullable();
            $table->longText('data')->nullable();
            $table->longText('fields')->nullable();
            $table->longText('settings')->nullable();
            $table->longText('ereg')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manta_modules');
    }
};
