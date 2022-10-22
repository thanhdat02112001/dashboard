<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = ['NAPAS', 'STB', 'VCB', 'VPB', '9PAY', 'MB', 'BIDV', 'ONEPAY'];
        foreach ($datas as $data) {
            DB::table('gateways')->insert(['gateway' => $data]);
        }
    }
}
