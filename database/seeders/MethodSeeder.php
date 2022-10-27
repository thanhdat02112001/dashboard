<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $methods = ['ATM_CARD', 'CREDIT_CARD', 'WALLET', 'COLLECTION', 'DISBURSEMENT', 'BUY_NOW_PAY_LATER'];
        foreach($methods as $method) {
            DB::table('payment_methods')->insert(['method' => $method]);
        }
    }
}
