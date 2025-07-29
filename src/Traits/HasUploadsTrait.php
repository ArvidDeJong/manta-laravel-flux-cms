<?php

namespace Manta\FluxCMS\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Manta\FluxCMS\Models\Upload;

trait HasUploadsTrait
{
    /**
     * @return HasOne
     */
    public function upload(): HasOne
    {
        return $this->hasOne(Upload::class, 'model_id')
            ->where('model', static::class)
            ->orderBy('sort', 'ASC');
    }

    /**
     * @return HasMany
     */
    public function uploads(): HasMany
    {
        return $this->hasMany(Upload::class, 'model_id')
            ->where('model', static::class)
            ->orderBy('sort', 'ASC');
    }

    /**
     * @return HasOne
     */
    public function document(): HasOne
    {
        return $this->hasOne(Upload::class, 'model_id')
            ->where('model', static::class)
            ->where('image', 0)
            ->orderBy('sort', 'ASC');
    }

    /**
     * @return HasMany
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Upload::class, 'model_id')
            ->where('model', static::class)
            ->where('image', 0)
            ->orderBy('sort', 'ASC');
    }

    /**
     * @return HasOne
     */
    public function image(): HasOne
    {
        return $this->hasOne(Upload::class, 'model_id')
            ->where('model', static::class)
            ->where(function ($query) {
                $query->where('image', 1)
                    ->orWhere('extension', 'pdf');
            })
            ->orderBy('main', 'DESC')
            ->orderBy('sort', 'ASC');
    }

    /**
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(Upload::class, 'model_id')
            ->where('model', static::class)
            ->where(function ($query) {
                $query->where('image', 1)
                    ->orWhere('extension', 'pdf');
            })
            ->orderBy('sort', 'ASC');
    }
}
