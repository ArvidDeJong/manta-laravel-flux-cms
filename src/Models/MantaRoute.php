<?php

namespace Manta\FluxCMS\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * MantaRoute model voor het beheren van routes.
 */
class MantaRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'updated_by',
        'uri',
        'name',
        'prefix',
        'active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatisch de created_by en updated_by velden invullen
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::user()->name ?? Auth::user()->email ?? Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::user()->name ?? Auth::user()->email ?? Auth::id();
            }
        });


    }

    /**
     * Scope voor actieve routes.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * Scope voor routes op basis van prefix.
     */
    public function scopeByPrefix(Builder $query, string $prefix): Builder
    {
        return $query->where('prefix', $prefix);
    }

    /**
     * Scope voor CMS routes (prefix = 'cms').
     */
    public function scopeCms(Builder $query): Builder
    {
        return $query->where('prefix', 'cms');
    }
}
