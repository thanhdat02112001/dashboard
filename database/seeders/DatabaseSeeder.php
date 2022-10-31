<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Merchant;
use App\Models\ReportTransaction;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call([
        //     MerchantSeeder::class,
        //     GatewaySeeder::class,
        //     BankSeeder::class,
        //     TranStatusSeeder::class,
        //     ChannelSeeder::class,
        //     MethodSeeder::class,
        // ]);
        // Card::factory()->count(100)->create();
        ReportTransaction::factory()->count(120)->create();
    }
}
