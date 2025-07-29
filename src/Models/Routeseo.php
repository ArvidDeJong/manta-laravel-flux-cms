<?php

namespace Manta\FluxCMS\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Manta\FluxCMS\Traits\HasUploadsTrait;

/**
 * Routeseo model voor SEO metadata van specifieke routes.
 */
class Routeseo extends Model
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
        'pid',
        'locale',
        'title',
        'route',
        'seo_title',
        'seo_description',
        'data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    // De data wordt automatisch als array gecast via $casts
    
    /**
     * Relatie naar het bedrijf.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
    
    /**
     * Scope voor actieve routes.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }
    
    /**
     * Scope voor het filteren op een specifieke route.
     *
     * @param Builder $query
     * @param string $route
     * @return Builder
     */
    public function scopeByRoute(Builder $query, string $route): Builder
    {
        return $query->where('route', $route);
    }
    
    /**
     * Scope voor het filteren op taal.
     *
     * @param Builder $query
     * @param string $locale
     * @return Builder
     */
    public function scopeByLocale(Builder $query, string $locale): Builder
    {
        return $query->where('locale', $locale);
    }
    
    /**
     * Scope voor het zoeken naar routes die overeenkomen met een zoekterm.
     *
     * @param Builder $query
     * @param string $term
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function (Builder $query) use ($term) {
            $query->where('title', 'like', "%{$term}%")
                  ->orWhere('route', 'like', "%{$term}%")
                  ->orWhere('seo_title', 'like', "%{$term}%")
                  ->orWhere('seo_description', 'like', "%{$term}%");
        });
    }
}
