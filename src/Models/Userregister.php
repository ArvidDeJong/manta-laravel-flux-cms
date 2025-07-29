<?php

namespace Manta\FluxCMS\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Manta\FluxCMS\Traits\HasUploadsTrait;

class Userregister extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUploadsTrait;

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
        'locale',
        'pid',
        'company',
        'title',
        'sex',
        'firstname',
        'middlename',
        'lastname',
        'email',
        'phone',
        'address',
        'address_nr',
        'zipcode',
        'city',
        'country',
        'kvk',
        'vat',
        'birthdate',
        'newsletters',
        'subject',
        'comment',
        'internal_contact',
        'ip',
        'password',
        'data',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birthdate' => 'date',
        'newsletters' => 'boolean',
        'data' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'full_name',
    ];

    /**
     * Scope a query to only include active user registers.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('deleted_at', null);
    }

    /**
     * Scope a query to search user registers by email.
     *
     * @param Builder $query
     * @param string $email
     * @return Builder
     */
    public function scopeByEmail(Builder $query, string $email): Builder
    {
        return $query->where('email', $email);
    }

    /**
     * Scope a query to search user registers.
     *
     * @param Builder $query
     * @param string $term
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function (Builder $query) use ($term) {
            $query->where('firstname', 'LIKE', "%{$term}%")
                ->orWhere('lastname', 'LIKE', "%{$term}%")
                ->orWhere('email', 'LIKE', "%{$term}%")
                ->orWhere('company', 'LIKE', "%{$term}%");
        });
    }

    /**
     * Get the company that this user register belongs to.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Set the password attribute - automatically hashes the password.
     *
     * @param string $value
     * @return void
     */
    public function setPasswordAttribute(string $value): void
    {
        if (Hash::needsRehash($value)) {
            $this->attributes['password'] = Hash::make($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    /**
     * Get full name attribute.
     * 
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        $middlename = $this->middlename ? ' ' . $this->middlename . ' ' : ' ';

        return $this->firstname . $middlename . $this->lastname;
    }
}
