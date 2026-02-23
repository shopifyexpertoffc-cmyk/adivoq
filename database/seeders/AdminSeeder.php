<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@creatorpay.in',
            'password' => Hash::make('password'),
            'phone' => '9569283474',
            'role' => 'super_admin',
            'is_active' => true,
        ]);
    }
}