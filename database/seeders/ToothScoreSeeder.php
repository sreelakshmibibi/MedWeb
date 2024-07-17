<?php

namespace Database\Seeders;

use App\Models\ToothScore;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ToothScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tooth_scores')->delete();
        ToothScore::create(['score' => 'Sound']);
        ToothScore::create(['score' => 'Decayed - D(d)']);
        ToothScore::create(['score' => 'Missing - M']);
        ToothScore::create(['score' => 'Filled - F']);
        ToothScore::create(['score' => 'For Extraction - X(x)']);
        ToothScore::create(['score' => 'Impacted']);
        ToothScore::create(['score' => 'Unerupted']);
        ToothScore::create(['score' => 'Morphological Difference']);
        ToothScore::create(['score' => 'Misplaced']);
    }
}
