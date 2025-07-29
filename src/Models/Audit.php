<?php

namespace Manta\FluxCMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** @package Manta\FluxCMS\Models */
class Audit extends Model
{
    use HasFactory;

    protected $fillable = [
        'datetime',
        'name',
        'action',
        'type',
        'model',
        'model_id',
        'user_id',
        'staff_id',
        'title',
        'comment',
        'error',
        'ip',
    ];

    /**
     * Relatie met User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relatie met Staff.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * @param mixed $query 
     * @param mixed $userId 
     * @return mixed 
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * @param mixed $query 
     * @param mixed $userId 
     * @return mixed 
     */
    public function scopeByStaff($query, $staffId)
    {
        return $query->where('staff_id', $staffId);
    }
}
