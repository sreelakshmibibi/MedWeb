<?php

namespace Database\Seeders;

use App\Models\SurfaceCondition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SurfaceConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('surface_conditions')->delete();
        SurfaceCondition::create(['condition' => 'Decayed']);
        SurfaceCondition::create(['condition' => 'Filled']);
        SurfaceCondition::create(['condition' => 'Have Fissure Sealant (HFS)']);
        SurfaceCondition::create(['condition' => 'Need Fissure Sealant (NFS)']);
    }
}
