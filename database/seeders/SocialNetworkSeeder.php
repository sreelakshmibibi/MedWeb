<?php

namespace Database\Seeders;

use App\Models\SocialNetwork;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialNetworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SocialNetwork::create(['network' => 'Facebook', 'status' => 'Y']);
        SocialNetwork::create(['network' => 'Twitter', 'status' => 'Y']);
        SocialNetwork::create(['network' => 'Instagram', 'status' => 'Y']);
        SocialNetwork::create(['network' => 'Whatsapp', 'status' => 'Y']);
        SocialNetwork::create(['network' => 'Referred by friend', 'status' => 'Y']);
        SocialNetwork::create(['network' => 'Location', 'status' => 'Y']);
        SocialNetwork::create(['network' => 'Brochure / Posters', 'status' => 'Y']);
        SocialNetwork::create(['network' => 'Others', 'status' => 'Y']);
        
    }
}
