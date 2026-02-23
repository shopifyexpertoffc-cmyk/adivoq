<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'contact_person',
        'website',
        'industry',
        'logo',
        'address',
        'gst_number',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Brand's campaigns
     */
    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    /**
     * Brand's invoices
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Total revenue from this brand
     */
    public function getTotalRevenueAttribute()
    {
        return $this->invoices()->where('status', 'paid')->sum('total_amount');
    }

    /**
     * Pending amount from this brand
     */
    public function getPendingAmountAttribute()
    {
        return $this->invoices()->whereIn('status', ['sent', 'viewed', 'partial'])->sum('balance_amount');
    }
}