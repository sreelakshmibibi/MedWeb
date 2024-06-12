<?php

namespace Database\Seeders;

use App\Models\WeekDay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WeekDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WeekDay::create(['name' => 'Monday', 'acronym' => 'MON', 'status' => 'Y']);
        WeekDay::create(['name' => 'Tuesday', 'acronym' => 'TUE', 'status' => 'Y']);
        WeekDay::create(['name' => 'Wednesday', 'acronym' => 'WED', 'status' => 'Y']);
        WeekDay::create(['name' => 'Thursday', 'acronym' => 'THUR', 'status' => 'Y']);
        WeekDay::create(['name' => 'Friday', 'acronym' => 'FRI', 'status' => 'Y']);
        WeekDay::create(['name' => 'Saturday', 'acronym' => 'SAT', 'status' => 'Y']);
        WeekDay::create(['name' => 'Sunday', 'acronym' => 'SUN', 'status' => 'Y']);
    }
}
