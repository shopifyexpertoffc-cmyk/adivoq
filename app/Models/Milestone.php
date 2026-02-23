<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'title',
        'description',
        'amount',
        'due_date',
        'status',
        'completed_at',
        'paid_at',
        'order',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'completed_at' => 'date',
        'paid_at' => 'date',
    ];

    /**
     * Milestone's campaign
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Milestone's invoice
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}