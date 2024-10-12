<?php

namespace Database\Seeders;

use App\Models\EmployeeType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employee_types')->delete();
        EmployeeType::create(['employee_type' => 'Contract', 'status' => 'Y', 'created_by' => 1, 'updated_by' => 1]);
        EmployeeType::create(['employee_type' => 'Permanent', 'status' => 'Y',  'created_by' => 1, 'updated_by' => 1]);
        
    }
}
