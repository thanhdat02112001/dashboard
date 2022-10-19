<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = [['id' => 'ecom', 'channel_name' => 'thanh toan truc tuyen'],
                    ['id' => 'invoice', 'channel_name' => 'thanh toan truc tiep']];
        foreach ($datas as $data) {
            DB::table('channels')->insert($data);
        }
    }
}
