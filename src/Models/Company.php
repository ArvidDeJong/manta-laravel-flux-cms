<?php

namespace Manta\FluxCMS\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Manta\FluxCMS\Traits\HasTranslationsTrait;
use Manta\FluxCMS\Traits\HasUploadsTrait;

/**
 * Company model voor het beheren van bedrijfs- en klantgegevens.
 */
class Company extends Model
{
    use SoftDeletes;
    use HasUploadsTrait;
    use HasTranslationsTrait;

    /**
     * Boot de model events voor het automatisch vastleggen van gebruikersinformatie.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function ($item) {
            $user = Auth::guard('staff')->user();
            if ($user) {
                $item->created_by = $user->name;
            }
        });

        static::updating(function ($item) {
            $user = Auth::guard('staff')->user();
            if ($user) {
                $item->updated_by = $user->name;
            }
        });

        static::deleting(function ($item) {
            $user = Auth::guard('staff')->user();
            if ($user) {
                $item->deleted_by = $user->name;
            }
        });
    }

    protected $fillable = [
        'created_by',
        'updated_by',
        'deleted_by',
        'host',
        'pid',
        'locale',
        'active',
        'administration',
        'identifier',
        'relation_nr',
        'debtor_nr',
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
        'latitude',
        'longitude',
        'data',        // Nieuwe kolom
    ];

    protected $casts = [
        'data' => 'array',
        'active' => 'boolean',
        'birthdate' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    protected $attributes = [
        'country' => 'nl',
        'active' => 1,
    ];

    // De data wordt automatisch als array gecast via $casts

    /**
     * Scope voor actieve bedrijven.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * Scope voor het zoeken op naam of nummer.
     *
     * @param Builder $query
     * @param string $term
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function (Builder $q) use ($term) {
            $q->where('company', 'like', "%{$term}%")
                ->orWhere('number', 'like', "%{$term}%")
                ->orWhere('lastname', 'like', "%{$term}%")
                ->orWhere('firstnames', 'like', "%{$term}%");
        });
    }

    /**
     * Scope voor het filteren op specifiek bedrijfsnummer.
     *
     * @param Builder $query
     * @param string $number
     * @return Builder
     */
    public function scopeByNumber(Builder $query, string $number): Builder
    {
        return $query->where('number', $number);
    }

    /**
     * Haalt alle opties op die bij dit bedrijf horen.
     *
     * @return HasMany
     */
    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }

    /**
     * Krijg de volledige naam van de persoon.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->initials} {$this->nameInsertion} {$this->lastname}");
    }
}
