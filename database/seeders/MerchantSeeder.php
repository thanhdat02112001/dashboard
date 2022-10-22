<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = [
            [
                'name' => 'Savor',
                'email' => 'contact@savor.vn',
                'phone' => '0399229999',
                'address' => 'Ha Noi'
            ],
            [
                'name' => 'Chicky',
                'email' => 'chicky@gmail.com',
                'phone' => '0734829829',
                'address' => 'Ha Noi'
            ],
            [
                'name' => 'Funtap',
                'email' => 'funtap@gmail.com',
                'phone' => '0934302821',
                'address' => 'Ha Noi'
            ],
            [
                'name' => 'Abby',
                'email' => 'support@abby.com',
                'phone' => '0929309222',
                'address' => 'Ha Noi'
            ],
            [
                'name' => 'FSS',
                'email' => 'fsstech@gmail.com',
                'phone' => '0910232382',
                'address' => 'Ha Noi'
            ],
        ];

        foreach ($datas as $data) {
            DB::table('merchants')->insert($data);
        }
    }
}
