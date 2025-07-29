<?php

namespace Manta\FluxCMS\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Translation extends Model
{
    /**
     * The "booted" method of the model.
     * 
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function ($item) {
            if (Auth::guard('staff')->check()) {
                $item->created_by = Auth::guard('staff')->user()->name;
            }
        });

        static::updating(function ($item) {
            if (Auth::guard('staff')->check()) {
                $item->updated_by = Auth::guard('staff')->user()->name;
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'key',
        'value',
        'file',
        'locale',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope a query to filter by locale.
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
     * Scope a query to filter by file.
     *
     * @param Builder $query
     * @param string $file
     * @return Builder
     */
    public function scopeByFile(Builder $query, string $file): Builder
    {
        return $query->where('file', $file);
    }

    /**
     * Scope a query to filter by key.
     *
     * @param Builder $query
     * @param string $key
     * @return Builder
     */
    public function scopeByKey(Builder $query, string $key): Builder
    {
        return $query->where('key', $key);
    }

    /**
     * Scope a query to search translations.
     *
     * @param Builder $query
     * @param string $term
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function (Builder $query) use ($term) {
            $query->where('key', 'LIKE', "%{$term}%")
                ->orWhere('value', 'LIKE', "%{$term}%");
        });
    }
}
