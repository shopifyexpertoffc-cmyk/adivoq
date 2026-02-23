<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    /**
     * Custom columns in tenants table
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'email',
            'phone',
            'plan',
            'company_name',
            'gst_number',
            'address',
            'city',
            'state',
            'country',
            'currency',
            'logo',
            'status',
            'trial_ends_at',
            'data',
        ];
    }

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'data' => 'array',
    ];

    public function onTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function getPrimaryDomain(): ?string
    {
        return $this->domains->first()?->domain;
    }
}