<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BloodStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $quantities = [3, 0, 2, 0, 1, 0, 5, 0]; // Sample quantities

        foreach ($bloodGroups as $index => $group) {
            DB::table('blood_stock')->insert([
                'blood_group' => $group,
                'quantity' => $quantities[$index],
                'last_updated' => now(),
            ]);
        }
    }
}
