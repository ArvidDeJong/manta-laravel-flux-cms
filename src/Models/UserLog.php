<?php

namespace Manta\FluxCMS\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that this log belongs to.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include logs from a specific user.
     *
     * @param Builder $query
     * @param int $userId
     * @return Builder
     */
    public function scopeByUserId(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include logs with a specific email.
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
     * Scope a query to only include logs from a specific IP address.
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
     * Create a new log entry.
     *
     * @param int|null $userId
     * @param string|null $email
     * @param string|null $ip
     * @param array|null $data
     * @return UserLog
     */
    public static function log(?int $userId = null, ?string $email = null, ?string $ip = null, ?array $data = []): UserLog
    {
        return self::create([
            'user_id' => $userId,
            'email' => $email,
            'ip' => $ip,
            'data' => $data,
        ]);
    }
}
