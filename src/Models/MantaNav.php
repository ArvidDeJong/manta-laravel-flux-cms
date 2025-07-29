<?php

namespace Manta\FluxCMS\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * MantaNav model voor het beheren van navigatie-items.
 */
class MantaNav extends Model
{
    use HasFactory;
    use SoftDeletes;

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
        'route',
        'url',
        'type',
        'data',
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
        'sort' => 'integer',
        'company_id' => 'integer',
        'pid' => 'integer',
        'data' => 'array',
    ];

    /**
     * Statisch geheugen voor de huidige gebruiker ID om meerdere Auth checks te voorkomen.
     * 
     * @var int|null
     */
    private static ?int $userId = null;

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

        static::deleting(function ($model) {
            if (Auth::check()) {
                $model->deleted_by = Auth::user()->name ?? Auth::user()->email ?? Auth::id();
                $model->save();
            }
        });
    }

    /**
     * Scope voor actieve navigatie-items.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * Scope voor navigatie-items op basis van locale.
     */
    public function scopeByLocale(Builder $query, string $locale): Builder
    {
        return $query->where('locale', $locale);
    }

    /**
     * Scope voor navigatie-items op basis van type.
     */
    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Relatie naar parent navigatie-item.
     */
    public function parent()
    {
        return $this->belongsTo(MantaNav::class, 'pid');
    }

    /**
     * Relatie naar child navigatie-items.
     */
    public function children()
    {
        return $this->hasMany(MantaNav::class, 'pid');
    }

    /**
     * Krijg alle actieve child navigatie-items.
     */
    public function activeChildren()
    {
        return $this->children()->active();
    }

    /**
     * Check of dit navigatie-item children heeft.
     */
    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    /**
     * Check of dit navigatie-item actieve children heeft.
     */
    public function hasActiveChildren(): bool
    {
        return $this->activeChildren()->exists();
    }
}
