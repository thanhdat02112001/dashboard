<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TranStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = ['init', 'processing', 'cancel', 'pending', 'success'];
        foreach ($statuses as $status) {
            DB::table('trans_status')->insert(['status' => $status]);
        }
    }
}
