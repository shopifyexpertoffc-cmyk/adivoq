<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waitlist extends Model
{
    use HasFactory;

    protected $table = 'waitlist';

    protected $fillable = [
        'uid',
        'name',
        'email',
        'phone',
        'creator_type',
        'followers',
        'monthly_invoices',
        'source',
        'position',
        'status',
        'ip_address',
        'user_agent',
        'invited_at',
    ];

    protected $casts = [
        'invited_at' => 'datetime',
    ];

    /**
     * Scope for pending entries
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for invited entries
     */
    public function scopeInvited($query)
    {
        return $query->where('status', 'invited');
    }
}