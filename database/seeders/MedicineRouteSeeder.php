<?php

namespace Database\Seeders;

use App\Models\MedicineRoute;
use Illuminate\Database\Seeder;

class MedicineRouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MedicineRoute::create(['route_name' => 'Oral', 'status' => 'Y']);
        MedicineRoute::create(['route_name' => 'Sublingual', 'status' => 'Y']);
        MedicineRoute::create(['route_name' => 'Rectum', 'status' => 'Y']);
        MedicineRoute::create(['route_name' => 'Intravascular', 'status' => 'Y']);
        MedicineRoute::create(['route_name' => 'Intramuscular', 'status' => 'Y']);
        MedicineRoute::create(['route_name' => 'Subcutaneous', 'status' => 'Y']);
        MedicineRoute::create(['route_name' => 'Inhalation', 'status' => 'Y']);
        MedicineRoute::create(['route_name' => 'Local Application', 'status' => 'Y']);
    }
}
