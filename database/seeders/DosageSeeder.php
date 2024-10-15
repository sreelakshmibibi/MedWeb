<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dosage;

class DosageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Dosage::create(['dos_name' => '0-0-1', 'status' => 'Y']);
        Dosage::create(['dos_name' => '0-1-0', 'status' => 'Y']);
        Dosage::create(['dos_name' => '0-1-1', 'status' => 'Y']);
        Dosage::create(['dos_name' => '1-0-0', 'status' => 'Y']);
        Dosage::create(['dos_name' => '1-0-1', 'status' => 'Y']);
        Dosage::create(['dos_name' => '1-1-0', 'status' => 'Y']);
        Dosage::create(['dos_name' => '1-1-1', 'status' => 'Y']);
        Dosage::create(['dos_name' => '0-0-0', 'status' => 'Y']);
    }
}
