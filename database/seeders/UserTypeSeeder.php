<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserType::create(['title' => 'Super Admin', 'priority' => 1, 'status' => 'N']);
        UserType::create(['title' => 'Admin', 'priority' => 2, 'status' => 'Y']);
        UserType::create(['title' => 'Doctor', 'priority' => 3, 'status' => 'Y']);
        UserType::create(['title' => 'Nurse', 'priority' => 4, 'status' => 'Y']);
        UserType::create(['title' => 'Reception', 'priority' => 5, 'status' => 'Y']);
    }
}
