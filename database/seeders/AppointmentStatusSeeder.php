<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AppoinmentStatus;

class AppointmentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AppoinmentStatus::create(['status' => 'Scheduled', 'st_color' => '#e4e6ef', 'tx_color' => 'black', 'stat' => '', 'indrop' => 'Y']);
        AppoinmentStatus::create(['status' => 'Waiting', 'st_color' => '#ffff00', 'tx_color' => 'black', 'stat' => '', 'indrop' => 'Y']);
        AppoinmentStatus::create(['status' => 'Unavailable', 'st_color' => '#4e342e', 'tx_color' => 'white', 'stat' => '', 'indrop' => 'Y']);
        AppoinmentStatus::create(['status' => 'Cancelled', 'st_color' => '#ee3158', 'tx_color' => 'white', 'stat' => '', 'indrop' => 'Y']);
        AppoinmentStatus::create(['status' => 'Completed', 'st_color' => '#05825f', 'tx_color' => 'white', 'stat' => '', 'indrop' => 'N']);
        AppoinmentStatus::create(['status' => 'Billing', 'st_color' => '#ff4081', 'tx_color' => 'white', 'stat' => '', 'indrop' => 'N']);
        AppoinmentStatus::create(['status' => 'Procedure', 'st_color' => '#ffa800', 'tx_color' => 'white', 'stat' => '', 'indrop' => 'N']);
        AppoinmentStatus::create(['status' => 'Missed', 'st_color' => '#3596f7', 'tx_color' => 'white', 'stat' => '', 'indrop' => 'N']);
        AppoinmentStatus::create(['status' => 'Re-Scheduled', 'st_color' => '#5156be', 'tx_color' => 'white', 'stat' => '', 'indrop' => 'N']);
        
    }
}
