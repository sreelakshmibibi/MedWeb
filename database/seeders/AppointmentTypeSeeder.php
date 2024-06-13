<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AppoinmentType;

class AppointmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AppoinmentType::create(['type' => 'Follow Up', 'status' => '']);
        AppoinmentType::create(['type' => 'New', 'status' => '']);
    }
}
