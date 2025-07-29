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
        Schema::create('uploads', function (Blueprint $table) {
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
            $table->boolean('main')->default(false);
            $table->integer('user_id')->nullable();
            $table->string('title')->nullable();
            $table->string('seo_title')->nullable();
            $table->boolean('private')->default(false);
            $table->string('disk')->nullable();
            $table->text('url')->nullable();
            $table->string('location')->nullable();
            $table->string('filename')->nullable();
            $table->string('extension')->nullable();
            $table->string('mime')->nullable();
            $table->integer('size')->nullable();
            $table->string('model')->nullable();
            $table->string('model_id')->nullable();
            $table->string('identifier')->nullable();
            $table->string('filenameOriginal')->nullable();
            $table->boolean('image')->default(false);
            $table->boolean('pdfLock')->default(false);
            $table->text('content')->nullable();
            $table->text('error')->nullable();
            $table->integer('pages')->default(0);
            $table->string('administration')->nullable()->comment('Administration column');
            $table->longText('data')->nullable();
        });
    }

    /**
     * Draai de migration terug.
     */
    public function down(): void
    {
        Schema::dropIfExists('uploads');
    }
};
