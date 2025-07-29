<?php

namespace Manta\FluxCMS\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * StaffLog model voor het bijhouden van staff login activiteit.
 */
class StaffLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'staff_id',
        'email', // If username incorrect
        'ip',
        'data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'array',
    ];

    // De data wordt automatisch als array gecast via $casts
    
    /**
     * Relatie naar de bijbehorende staff.
     *
     * @return BelongsTo
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }
    
    /**
     * Scope voor het filteren op staff id.
     *
     * @param Builder $query
     * @param int $staffId
     * @return Builder
     */
    public function scopeByStaffId(Builder $query, int $staffId): Builder
    {
        return $query->where('staff_id', $staffId);
    }
    
    /**
     * Scope voor het filteren op email.
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
     * Scope voor het filteren op IP adres.
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
     * Voegt een log toe voor een staff member.
     *
     * @param int|null $staffId
     * @param string|null $email
     * @param string|null $ip
     * @param array $data
     * @return self
     */
    public static function log(?int $staffId, ?string $email, ?string $ip, array $data = []): self
    {
        return self::create([
            'staff_id' => $staffId,
            'email' => $email,
            'ip' => $ip,
            'data' => $data
        ]);
    }
}
