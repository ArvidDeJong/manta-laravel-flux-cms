<?php

namespace Manta\FluxCMS\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasTranslationsTrait
{
    public function translations(): HasMany
    {
        return $this->hasMany(static::class, 'pid', 'id');
    }

    public function hasTranslation(string $locale): bool
    {
        return $this->translations()->where('locale', $locale)->exists();
    }

    public function translation(string $locale)
    {
        return $this->translations()->where('locale', $locale)->first();
    }
}
