<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DonorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $donors = [
            [
                'full_name' => 'Rashik',
                'age' => 32,
                'gender' => 'Male',
                'blood_group' => 'O+',
                'phone' => '1234567890',
                'email' => 'rashik@email.com',
                'address' => 'Sirajganj',
                'last_donation_date' => '2024-12-15',
                'status' => 'active',
            ],
            [
                'full_name' => 'Sadia',
                'age' => 28,
                'gender' => 'Female',
                'blood_group' => 'A+',
                'phone' => '9876543210',
                'email' => 'sadia@email.com',
                'address' => 'Dhaka',
                'last_donation_date' => '2025-01-05',
                'status' => 'active',
            ],
            [
                'full_name' => 'Akash',
                'age' => 45,
                'gender' => 'Male',
                'blood_group' => 'B+',
                'phone' => '5551234567',
                'email' => 'akash@email.com',
                'address' => 'Sirajganj',
                'last_donation_date' => '2024-11-20',
                'status' => 'active',
            ],
        ];

        foreach ($donors as $donor) {
            DB::table('donors')->insert(array_merge($donor, [
                'created_at' => now(),
            ]));
        }
    }
}
