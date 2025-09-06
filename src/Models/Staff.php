<?php

namespace Manta\FluxCMS\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Manta\FluxCMS\Notifications\StaffResetPasswordNotification;
use Manta\FluxCMS\Traits\HasUploadsTrait;

/**
 * Staff model voor het beheren van medewerkers/gebruikersaccounts.
 */
class Staff extends Authenticatable implements CanResetPasswordContract
{
    use HasFactory;
    use SoftDeletes;
    use Notifiable;
    use HasUploadsTrait;
    use CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
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
        'name',
        'email',
        'phone',
        'email_verified_at',
        'password',
        'lastLogin',
        'code',
        'admin',
        'developer',
        'comments',
        'data',
        'rights',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'array',
        'active' => 'boolean',
        'admin' => 'boolean',
        'developer' => 'boolean',
        'email_verified_at' => 'datetime',
        'lastLogin' => 'datetime',
        'rights' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [];

    // De data wordt automatisch als array gecast via $casts

    /**
     * Stuur een wachtwoordreset notificatie naar de medewerker.
     * 
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new StaffResetPasswordNotification($token));
    }

    /**
     * Scope voor actieve medewerkers.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * Scope voor administrators.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeAdmin(Builder $query): Builder
    {
        return $query->where('admin', true);
    }

    /**
     * Scope voor developers.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeDeveloper(Builder $query): Builder
    {
        return $query->where('developer', true);
    }

    /**
     * Scope voor het zoeken naar medewerkers op naam of email.
     *
     * @param Builder $query
     * @param string $term
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function (Builder $q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%");
        });
    }

    /**
     * Relatie naar het bedrijf.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function hasAccess($right = '')
    {
        // Check if the rights array is defined and not empty
        if (!isset($this->rights) || empty($this->rights)) {

            return false;
        }

        // Split the provided right by a dot to handle nested access, e.g., "user.manager"
        $keys = explode('.', $right);


        // Reduce through the rights array to find the nested value
        $access = $this->rights;
        foreach ($keys as $key) {
            if (isset($access[$key])) {
                $access = $access[$key];
            } else {
                return false; // Key not found, access denied
            }
        }

        // Ensure the final value is boolean and true
        return $access === true;
    }
}
