<?php

namespace Database\Seeders;

use App\Models\TeethRow;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeethRowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('teeth_rows')->delete();
        TeethRow::create(['teeth_nos' => '51,52,53,54,55,61,62,63,64,65']);
        TeethRow::create(['teeth_nos' => '11,12,13,14,15,16,17,18,21,22,23,24,25,26,27,28']);
        TeethRow::create(['teeth_nos' => '31,32,33,34,35,36,37,38,41,42,43,44,45,46,47,48']);
        TeethRow::create(['teeth_nos' => '71,72,73,74,75,81,82,83,84,85']);
    }
}
