<?php

namespace Database\Seeders;

use App\Models\CardPay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CardPaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update all existing records in 'card_pays' table to set 'status' to 'Y'
        DB::table('card_pays')->update(['status' => 'N']);

        // Create new records
        CardPay::create(['card_name' => 'HDFC', 'service_charge_perc' => '1.5', 'status' => 'Y']);
        CardPay::create(['card_name' => 'Axis', 'service_charge_perc' => '1.6', 'status' => 'Y']);
        CardPay::create(['card_name' => 'Kotak', 'service_charge_perc' => '2.5', 'status' => 'Y']);
    }
}
