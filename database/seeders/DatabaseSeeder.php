<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'is_admin' => 1,
        ]);

        $this->call([
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
    }
}
