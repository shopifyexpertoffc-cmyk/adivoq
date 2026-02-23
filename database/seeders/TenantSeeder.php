<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::create([
            'id' => 'demo',
            'name' => 'Demo User',
            'email' => 'demo@adivoq.com',
            'phone' => '9876543210',
            'plan' => 'pro',
            'company_name' => 'Demo Creator Studio',
            'country' => 'IN',
            'currency' => 'INR',
            'status' => 'active',
            'trial_ends_at' => now()->addDays(14),
        ]);

        $tenant->domains()->create([
            'domain' => 'demo.adivoq.com', // 👈 current live domain
        ]);
    }
}
