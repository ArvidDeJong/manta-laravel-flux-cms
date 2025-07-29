<?php

namespace Manta\FluxCMS\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/** @package Manta\FluxCMS\Models */
class Firewall extends Model
{
    use SoftDeletes;

    /**
     * Model Events
     */
    protected static function booted()
    {
        static::creating(function ($firewall) {
            $firewall->created_by = auth('staff')->user()->name;
        });

        static::updating(function ($firewall) {
            $firewall->updated_by = auth('staff')->user()->name;
        });

        static::deleting(function ($firewall) {
            $firewall->deleted_by = auth('staff')->user()->name;
        });
    }

    protected $fillable = [
        'created_by',
        'updated_by',
        'deleted_by',
        'company_id',
        'host',
        'IP',
        'hostname',
        'email',
        'status',
        'comment',
        'data',        // Nieuwe kolom
    ];

    protected $casts = [
        'status' => 'boolean',
        'data' => 'array',
    ];

    // De data wordt automatisch als array gecast via $casts

    /**
     * Relations
     */
    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Scopes
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    /**
     * Accessors and Mutators
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->hostname ?: $this->host;
    }

    /**
     * Controleert of een domein bestaat en MX records heeft
     * 
     * @param string $domain Domein om te controleren
     * @return string|null Foutmelding of null bij succes
     */
    public function checkDomainAndMx(string $domain): ?string
    {
        if (gethostbyname($domain) && checkdnsrr($domain, 'MX')) {
            return null;
        } elseif (gethostbyname($domain)) {
            return "The domain $domain exists but has no MX records.";
        } else {
            return "The domain $domain does not exist.";
        }
    }
}
