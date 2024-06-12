<?php

namespace Database\Seeders;

use App\Models\ClinicType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClinicTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClinicType::create(['name' => 'Dental', 'status' => 'Y']);
        ClinicType::create(['name' => 'Dermatology', 'status' => 'N']);

    }
}
