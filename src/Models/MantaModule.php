<?php

namespace Manta\FluxCMS\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * MantaModule model for managing CMS modules.
 */
class MantaModule extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'created_by',
        'updated_by',
        'deleted_by',
        'company_id',
        'host',
        'locale',
        'active',
        'sort',
        'name',
        'title',
        'module_name',
        'tabtitle',
        'description',
        'route',
        'url',
        'icon',
        'type',
        'rights',
        'data',
        'fields',
        'settings',
        'ereg',
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
        'module_name' => 'array',
        'data' => 'array',
        'fields' => 'array',
        'settings' => 'array',
        'ereg' => 'array',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically fill created_by and updated_by fields
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
     * Scope for active modules.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * Scope for modules by locale.
     */
    public function scopeByLocale(Builder $query, string $locale): Builder
    {
        return $query->where('locale', $locale);
    }

    /**
     * Scope for modules by type.
     */
    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for CMS modules.
     */
    public function scopeCms(Builder $query): Builder
    {
        return $query->where('type', 'cms');
    }

    /**
     * Scope for webshop modules.
     */
    public function scopeWebshop(Builder $query): Builder
    {
        return $query->where('type', 'webshop');
    }

    /**
     * Scope for tools modules.
     */
    public function scopeTools(Builder $query): Builder
    {
        return $query->where('type', 'tools');
    }

    /**
     * Scope for dev modules.
     */
    public function scopeDev(Builder $query): Builder
    {
        return $query->where('type', 'dev');
    }

    /**
     * Get modules ordered by sort.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort', 'asc');
    }

    /**
     * Check if module has a route.
     */
    public function hasRoute(): bool
    {
        return !empty($this->route);
    }

    /**
     * Check if module has a URL.
     */
    public function hasUrl(): bool
    {
        return !empty($this->url);
    }

    /**
     * Get the module's link (route or URL).
     */
    public function getLink(): string
    {
        if ($this->hasRoute()) {
            return route($this->route);
        }

        return $this->url ?? '#';
    }
}
