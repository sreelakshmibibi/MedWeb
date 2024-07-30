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
        TreatmentPlan::create(['plan' => 'Porcelain Crowns','cost' => '1000', 'status' => 'Y']);
        TreatmentPlan::create(['plan' => 'Metal Crowns','cost' => '1000', 'status' => 'Y']);
        TreatmentPlan::create(['plan' => 'Zirconia Crowns','cost' => '1000', 'status' => 'Y']);
        TreatmentPlan::create(['plan' => 'Composite Resin Crowns','cost' => '1000', 'status' => 'Y']);
        TreatmentPlan::create(['plan' => 'PFM','cost' => '1000', 'status' => 'Y']);
        TreatmentPlan::create(['plan' => 'Metal Braces','cost' => '10000', 'status' => 'Y']);
        TreatmentPlan::create(['plan' => 'Ceramic braces', 'cost' => '10000', 'status' => 'Y']);
        TreatmentPlan::create(['plan' => 'Lingual braces', 'cost' => '10000','status' => 'Y']);
        TreatmentPlan::create(['plan' => 'Self-ligating braces','cost' => '10000', 'status' => 'Y']);
        TreatmentPlan::create(['plan' => 'Clear aligners', 'cost' => '10000','status' => 'Y']);
        TreatmentPlan::create(['plan' => 'Partial dentures','cost' => '20000', 'status' => 'Y']);
        TreatmentPlan::create(['plan' => 'Full dentures', 'cost' => '20000','status' => 'Y']);
        TreatmentPlan::create(['plan' => 'Removable dentures', 'cost' => '20000', 'status' => 'Y']);
        TreatmentPlan::create(['plan' => 'Flexible dentures', 'cost' => '20000', 'status' => 'Y']);
        
    }
}
