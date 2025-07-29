<?php

namespace Manta\FluxCMS\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Manta\FluxCMS\Traits\HasTranslations;
use Manta\FluxCMS\Traits\HasUploadsTrait;

class Contactperson extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUploadsTrait;
    use HasTranslations;


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'created_by',
        'updated_by',
        'deleted_by',
        'company_id',
        'host',
        'pid',
        'locale',
        'active',
        'sort',
        'title',
        'slug',
        'content',
        'company',
        'department',
        'sex',
        'firstname',
        'lastname',
        'email',
        'phone',
        'address',
        'address_nr',
        'zipcode',
        'city',
        'country',
        'birthdate',
        'social_1',
        'social_2',
        'social_3',
        'social_4',
        'social_5',
        'social_6',
        'data',        // Nieuwe kolom
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'boolean',
        'data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'birthdate' => 'date',
    ];

    /**
     * Get the company that this contactperson belongs to.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [];

    /**
     * Scope a query to only include active contactpeople.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', 1);
    }

    /**
     * Scope a query to search contactpeople by name (firstname and lastname).
     *
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeSearchByName(Builder $query, string $search): Builder
    {
        return $query->where(function (Builder $query) use ($search) {
            $query->where('firstname', 'LIKE', "%{$search}%")
                ->orWhere('lastname', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Scope a query to search contactpeople by email.
     *
     * @param Builder $query
     * @param string $email
     * @return Builder
     */
    public function scopeSearchByEmail(Builder $query, string $email): Builder
    {
        return $query->where('email', 'LIKE', "%{$email}%");
    }

    /**
     * Scope a query to search contactpeople by company.
     *
     * @param Builder $query
     * @param string $company
     * @return Builder
     */
    public function scopeSearchByCompany(Builder $query, string $company): Builder
    {
        return $query->where('company', 'LIKE', "%{$company}%");
    }
}
