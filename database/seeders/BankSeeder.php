<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banks= [
            [
                'bank_code' => 'STB',
                'bank_name' => 'Ngan hang TMCP Sai Gon Thuong Tin'
            ],
            [
                'bank_code' => 'VCB',
                'bank_name' => 'Ngân hàng thương mại cổ phần Ngoại thương Việt Nam'
            ],
            [
                'bank_code' => 'VPB',
                'bank_name' => 'Ngân hàng Thương mại cổ phần Việt Nam Thịnh Vượng'
            ],
            [
                'bank_code' => 'MB',
                'bank_name' => 'Ngân hàng Quân đội'
            ],
            [
                'bank_code' => 'BIDV',
                'bank_name' => 'Ngân hàng Thương mại cổ phần Đầu tư và Phát triển Việt Nam'
            ],
        ];
        foreach ($banks as $bank) {
            DB::table('banks')->insert($bank);
        }
    }
}
