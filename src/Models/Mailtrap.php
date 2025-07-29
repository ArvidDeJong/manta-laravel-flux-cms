<?php

namespace Manta\FluxCMS\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Mailtrap model voor het bijhouden van email events en tracking.
 */
class Mailtrap extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'event',
        'timestamp',
        'sending_stream',
        'category',
        'message_id',
        'event_id',
        'custom_variables',
        'data',        // Nieuwe kolom
    ];

    protected $casts = [
        'data' => 'array',
        'custom_variables' => 'array', // Zorgt ervoor dat JSON automatisch wordt geconverteerd naar een array
    ];

    // De data en custom_variables worden automatisch als array gecast via $casts
    
    /**
     * Scope voor het vinden van een specifiek email adres.
     *
     * @param Builder $query
     * @param string $email
     * @return Builder
     */
    public function scopeByEmail(Builder $query, string $email): Builder
    {
        return $query->where('email', $email);
    }
    
    /**
     * Scope voor het vinden van een specifiek event type.
     *
     * @param Builder $query
     * @param string $event
     * @return Builder
     */
    public function scopeByEvent(Builder $query, string $event): Builder
    {
        return $query->where('event', $event);
    }
    
    /**
     * Scope voor het vinden van een specifieke categorie.
     *
     * @param Builder $query
     * @param string $category
     * @return Builder
     */
    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }
}
