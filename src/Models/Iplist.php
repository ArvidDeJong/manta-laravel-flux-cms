<?php

namespace Manta\FluxCMS\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Iplist model voor het bijhouden van IP-adressen en statistieken.
 */
class Iplist extends Model
{
    use HasFactory;

    // Velden die ingevuld mogen worden
    protected $fillable = ['ip', 'times', 'description', 'white', 'data'];

    // Optioneel: casts voor booleans
    protected $casts = [
        'data' => 'array',
        'white' => 'boolean',
    ];

    // De data wordt automatisch als array gecast via $casts
    
    /**
     * Scope voor het vinden van een IP-adres.
     *
     * @param Builder $query
     * @param string $ip
     * @return Builder
     */
    public function scopeByIp(Builder $query, string $ip): Builder
    {
        return $query->where('ip', $ip);
    }
    
    /**
     * Scope voor het vinden van whitelist items.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWhitelisted(Builder $query): Builder
    {
        return $query->where('white', true);
    }
}
