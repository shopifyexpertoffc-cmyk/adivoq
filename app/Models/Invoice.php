<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'brand_id',
        'campaign_id',
        'milestone_id',
        'user_id',
        'invoice_date',
        'due_date',
        'currency',
        'subtotal',
        'discount',
        'discount_type',
        'tax_enabled',
        'tax_type',
        'tax_rate',
        'tax_amount',
        'gst_number',
        'tds_applicable',
        'tds_rate',
        'tds_amount',
        'total_amount',
        'paid_amount',
        'balance_amount',
        'status',
        'client_name',
        'client_email',
        'client_address',
        'client_gst',
        'items',
        'notes',
        'terms',
        'sent_at',
        'viewed_at',
        'paid_at',
        'payment_method',
        'transaction_id',
        'pdf_path',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'tds_rate' => 'decimal:2',
        'tds_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'tax_enabled' => 'boolean',
        'tds_applicable' => 'boolean',
        'items' => 'array',
        'sent_at' => 'datetime',
        'viewed_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    /**
     * Invoice's brand
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Invoice's campaign
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Invoice's milestone
     */
    public function milestone()
    {
        return $this->belongsTo(Milestone::class);
    }

    /**
     * Invoice's user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Invoice's payments
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Check if overdue
     */
    public function isOverdue(): bool
    {
        return $this->due_date->isPast() && $this->status !== 'paid';
    }

    /**
     * Generate invoice number
     */
    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV-';
        $year = date('Y');
        $lastInvoice = self::whereYear('created_at', $year)->latest()->first();
        
        if ($lastInvoice) {
            $lastNumber = intval(substr($lastInvoice->invoice_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return $prefix . $year . '-' . $newNumber;
    }
}