<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'brand_id',
        'user_id',
        'name',
        'description',
        'platform',
        'campaign_type',
        'total_amount',
        'currency',
        'advance_amount',
        'paid_amount',
        'start_date',
        'end_date',
        'deliverable_date',
        'status',
        'payment_status',
        'deliverables',
        'notes',
        'agency_commission_percent',
        'manager_commission_percent',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'advance_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'deliverable_date' => 'date',
        'deliverables' => 'array',
        'agency_commission_percent' => 'decimal:2',
        'manager_commission_percent' => 'decimal:2',
    ];

    /**
     * Campaign's brand
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Campaign's user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Campaign's milestones
     */
    public function milestones()
    {
        return $this->hasMany(Milestone::class)->orderBy('order');
    }

    /**
     * Campaign's invoices
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Balance amount
     */
    public function getBalanceAmountAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }

    /**
     * Payment progress percentage
     */
    public function getPaymentProgressAttribute()
    {
        if ($this->total_amount == 0) return 0;
        return round(($this->paid_amount / $this->total_amount) * 100);
    }
}