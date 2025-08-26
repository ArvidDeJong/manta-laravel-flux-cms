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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->string('host')->nullable();
            $table->integer('pid')->nullable();
            $table->string('locale')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('sort')->default(1);
            $table->string('administration')->nullable()->comment('Administration column');
            $table->string('identifier')->nullable()->comment('Identifier column');
            $table->string('relation_nr')->nullable();
            $table->string('debtor_nr')->nullable();
            $table->string('user_nr')->nullable();
            $table->string('number')->nullable();
            $table->string('sex')->nullable();
            $table->string('initials')->nullable();
            $table->string('lastname')->nullable();
            $table->string('firstnames')->nullable();
            $table->string('nameInsertion')->nullable();
            $table->string('company')->nullable();
            $table->string('companyNr')->nullable();
            $table->string('taxNr')->nullable();
            $table->string('address')->nullable();
            $table->string('housenumber')->nullable();
            $table->string('addressSuffix')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('nl');
            $table->string('state')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone2')->nullable();
            $table->string('bsn')->nullable();
            $table->string('iban')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('birthcity')->nullable();
            $table->string('title')->nullable();
            $table->text('excerpt')->nullable();
            $table->text('description')->nullable();
            $table->string('google_maps_embed')->nullable();
            $table->decimal('latitude', 10, 8)->nullable()->comment('Latitude coordinate');
            $table->decimal('longitude', 11, 8)->nullable()->comment('Longitude coordinate');
            $table->longText('data')->nullable();
        });
    }

    /**
     * Draai de migration terug.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
