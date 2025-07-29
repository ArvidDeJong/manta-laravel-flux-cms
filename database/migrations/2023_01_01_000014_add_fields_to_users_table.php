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
        Schema::table('users', function (Blueprint $table) {
            // Softdelete en tracking columns
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            // Two factor authentication
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();

            // Team info
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();

            // Extra velden
            $table->boolean('must_change_password')->default(true);
            $table->foreignId('company_id')->nullable();
            $table->foreignId('contactperson_id')->nullable();
            $table->string('host')->nullable();
            $table->integer('pid')->nullable();
            $table->string('locale')->nullable();
            $table->boolean('active')->default(true);
            $table->string('administration')->nullable();
            $table->string('identifier')->nullable();
            $table->string('relation_nr')->nullable();
            $table->string('debtor_nr')->nullable();
            $table->string('creditor_nr')->nullable();
            $table->string('address_nr')->nullable();
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
            $table->date('birthdate')->nullable();
            $table->string('birthcity')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone2')->nullable();
            $table->string('bsn')->nullable();
            $table->string('iban')->nullable();
            $table->string('maritalStatus')->nullable();
            $table->dateTime('lastLogin')->nullable();
            $table->string('code')->nullable();
            $table->boolean('admin')->default(false);
            $table->boolean('developer')->default(false);
            $table->text('comment')->nullable();
            $table->longText('data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Verwijder de toegevoegde velden
            $table->dropSoftDeletes();
            $table->dropColumn([
                'created_by',
                'updated_by',
                'deleted_by',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'two_factor_confirmed_at',
                'current_team_id',
                'profile_photo_path',
                'must_change_password',
                'company_id',
                'contactperson_id',
                'host',
                'pid',
                'locale',
                'active',
                'administration',
                'identifier',
                'relation_nr',
                'debtor_nr',
                'creditor_nr',
                'address_nr',
                'user_nr',
                'number',
                'sex',
                'initials',
                'lastname',
                'firstnames',
                'nameInsertion',
                'company',
                'companyNr',
                'taxNr',
                'address',
                'housenumber',
                'addressSuffix',
                'zipcode',
                'city',
                'country',
                'state',
                'birthdate',
                'birthcity',
                'phone',
                'phone2',
                'bsn',
                'iban',
                'maritalStatus',
                'lastLogin',
                'code',
                'admin',
                'developer',
                'comment'
            ]);
        });
    }
};
