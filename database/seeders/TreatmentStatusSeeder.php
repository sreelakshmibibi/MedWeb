<?php

namespace Database\Seeders;

use App\Models\TreatmentStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TreatmentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("treatment_statuses")->delete();
        TreatmentStatus::create(['status' => 'Completed']);
        TreatmentStatus::create(['status' => 'Follow up required']);
    }
}
