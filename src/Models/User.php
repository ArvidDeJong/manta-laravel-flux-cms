<?php

namespace Manta\FluxCMS\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Sanctum API Tokens verwijderd voor Laravel 12 compatibiliteit
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements MustVerifyEmail
{
    // HasApiTokens trait verwijderd voor Laravel 12 compatibiliteit
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'remember_token',
        'current_team_id',
        'profile_photo_path',
        'must_change_password',
        'created_by',
        'updated_by',
        'deleted_by',
        'company_id',
        'host',
        'pid',
        'locale',
        'active',
        'relation_nr',
        'debtor_nr',
        'creditor_nr',
        'user_nr',
        'address_nr',
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
        'comment',
        'contactperson_id',
        'administration',
        'identifier',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'must_change_password' => 'boolean',
        'active' => 'boolean',
        'admin' => 'boolean',
        'developer' => 'boolean',
        'lastLogin' => 'datetime',
        'birthdate' => 'date',
    ];

    /**
     * The "booted" method of the model.
     * 
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function ($user) {
            if (Auth::check()) {
                $user->created_by = Auth::user()->name;
            }
        });

        static::updating(function ($user) {
            if (Auth::check()) {
                $user->updated_by = Auth::user()->name;
            }
        });

        static::deleting(function ($user) {
            if (Auth::check()) {
                $user->deleted_by = Auth::user()->name;
            }
        });
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
        $insertion = $this->nameInsertion ? ' ' . $this->nameInsertion . ' ' : ' ';

        if ($this->firstnames && $this->lastname) {
            return $this->firstnames . $insertion . $this->lastname;
        } elseif ($this->name) {
            return $this->name;
        } else {
            return $this->email;
        }
    }

    /**
     * Get the company associated with the user.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the company contact person associated with the user.
     *
     * @return BelongsTo
     */
    public function contactperson(): BelongsTo
    {
        return $this->belongsTo(Contactperson::class);
    }

    /**
     * Get the user logs for this user.
     *
     * @return HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(UserLog::class);
    }

    /**
     * Scope a query to only include active users.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * Scope a query to only include admin users.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeAdmin(Builder $query): Builder
    {
        return $query->where('admin', true);
    }

    /**
     * Scope a query to only include developer users.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeDeveloper(Builder $query): Builder
    {
        return $query->where('developer', true);
    }

    /**
     * Scope a query to search users.
     *
     * @param Builder $query
     * @param string $term
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function (Builder $query) use ($term) {
            $query->where('name', 'LIKE', "%{$term}%")
                ->orWhere('email', 'LIKE', "%{$term}%")
                ->orWhere('firstnames', 'LIKE', "%{$term}%")
                ->orWhere('lastname', 'LIKE', "%{$term}%")
                ->orWhere('company', 'LIKE', "%{$term}%");
        });
    }

    /**
     * Scope a query to filter by company.
     *
     * @param Builder $query
     * @param int $companyId
     * @return Builder
     */
    public function scopeByCompany(Builder $query, int $companyId): Builder
    {
        return $query->where('company_id', $companyId);
    }
}
