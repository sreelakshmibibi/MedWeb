<?php

namespace Database\Seeders;

use App\Models\AppointmentType;
use Illuminate\Database\Seeder;

class AppointmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AppointmentType::create(['type' => 'Follow Up', 'status' => '']);
        AppointmentType::create(['type' => 'New', 'status' => '']);
    }
}
