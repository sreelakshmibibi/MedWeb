<?php

namespace Database\Seeders;

use App\Models\TreatmentPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TreatmentPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('treatment_plans')->delete();
        TreatmentPlan::create(['plan' => 'Porcelain Crowns', 'status' => 'Y']);
        TreatmentPlan::create(['plan' => 'Metal Crowns', 'status' => 'Y']);
        TreatmentPlan::create(['plan' => 'Zirconia Crowns', 'status' => 'Y']);
        TreatmentPlan::create(['plan' => 'Composite Resin Crowns', 'status' => 'Y']);
        TreatmentPlan::create(['plan' => 'PFM', 'status' => 'Y']);
        TreatmentPlan::create(['plan' => 'Metal Braces', 'status' => 'Y']);
        TreatmentPlan::create(['plan' => 'Ceramic braces', 'status' => 'Y']);
        TreatmentPlan::create(['plan' => 'Lingual braces', 'status' => 'Y']);
        TreatmentPlan::create(['plan' => 'Self-ligating braces', 'status' => 'Y']);
        TreatmentPlan::create(['plan' => 'Clear aligners', 'status' => 'Y']);
        TreatmentPlan::create(['plan' => 'Partial dentures', 'status' => 'Y']);
        TreatmentPlan::create(['plan' => 'Full dentures', 'status' => 'Y']);
        TreatmentPlan::create(['plan' => 'Removable dentures', 'status' => 'Y']);
        TreatmentPlan::create(['plan' => 'Flexible dentures', 'status' => 'Y']);
        
    }
}
