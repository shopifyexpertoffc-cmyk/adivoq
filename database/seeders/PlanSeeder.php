<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Perfect for getting started',
                'price_monthly' => 0,
                'price_yearly' => 0,
                'currency' => 'INR',
                'max_invoices_per_month' => 5,
                'max_brands' => 5,
                'max_campaigns' => 5,
                'max_team_members' => 1,
                'features' => [
                    'Basic revenue dashboard',
                    'Single currency',
                    'Email support',
                    'Mobile app access',
                ],
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 1,
                'trial_days' => 0,
            ],
            [
                'name' => 'Creator Pro',
                'slug' => 'creator-pro',
                'description' => 'For serious creators',
                'price_monthly' => 999,
                'price_yearly' => 9990,
                'currency' => 'INR',
                'max_invoices_per_month' => -1, // Unlimited
                'max_brands' => -1,
                'max_campaigns' => -1,
                'max_team_members' => 3,
                'features' => [
                    'Unlimited invoices',
                    'Milestone tracking',
                    'Multi-currency support',
                    'GST/VAT automation',
                    'Brand deal manager',
                    'Payment reminders',
                    'Tax estimates',
                    'WhatsApp integration',
                    'Priority support',
                ],
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2,
                'trial_days' => 14,
            ],
            [
                'name' => 'Agency',
                'slug' => 'agency',
                'description' => 'For talent agencies & teams',
                'price_monthly' => 10000,
                'price_yearly' => 100000,
                'currency' => 'INR',
                'max_invoices_per_month' => -1,
                'max_brands' => -1,
                'max_campaigns' => -1,
                'max_team_members' => 50,
                'features' => [
                    'Everything in Pro',
                    'Up to 50 team members',
                    'Team management',
                    'Commission splits',
                    'White-label option',
                    'API access',
                    'Custom branding',
                    'Dedicated support',
                    'Analytics & reports',
                ],
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 3,
                'trial_days' => 14,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}