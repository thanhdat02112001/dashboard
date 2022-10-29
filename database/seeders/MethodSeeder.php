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
        $methods = ['BUY_NOW_PAY_LATER', 'LOCAL_DEBIT_CARD', 'INTERNATIONAL_CARD', 'E-WALLET', 'BANK_TRANSFER'];
        foreach($methods as $method) {
            DB::table('payment_methods')->insert(['method' => $method]);
        }
    }
}
