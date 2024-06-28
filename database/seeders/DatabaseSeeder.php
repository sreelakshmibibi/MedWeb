<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //User::factory(10)->create();
        $this->call([
            MenuItemsSeeder::class,
            UserTypeSeeder::class,
            SocialNetworkSeeder::class,
            CountrySeeder::class,
            StateSeeder::class,
            CitySeeder::class,
            AppointmentStatusSeeder::class,
            AppointmentTypeSeeder::class,
            DosageSeeder::class,
            ClinicTypeSeeder::class,
            WeekDaySeeder::class,
            // Other seeders...
        ]);
        $adminUser = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'is_admin' => 1,
        ]);

        $role = Role::findByName('Admin');
        $adminUser->assignRole($role);

        $doctorUser = User::factory()->create([
            'name' => 'Doctor',
            'email' => 'doctor@gmail.com',
            'is_doctor' => 1,
        ]);

        $drole = Role::findByName('Doctor');
        $doctorUser->assignRole($drole);

        
    }
}
