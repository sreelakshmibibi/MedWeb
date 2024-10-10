<?php

namespace Database\Seeders;

use App\Models\Shade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('shades')->delete();
        Shade::create(['shade_name' => 'A1']);
        Shade::create(['shade_name' => 'A2']);
        Shade::create(['shade_name' => 'A3']);
        Shade::create(['shade_name' => 'A4']);
        Shade::create(['shade_name' => 'A5']);
    }
}
