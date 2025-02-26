<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $penjualan = [];
        for ($i = 1; $i <= 10; $i++) {
            $penjualan[] = [
                'no_penjualan' => 'PJ' . now()->format('Ymd')
                 . str_pad($i, 3, '0', STR_PAD_LEFT),
                'tanggal_penjualan' => now()->subDays(rand(1, 30)),
                'id_user' => rand(1, 3),
            ];
        }

        DB::table('t_penjualan')->insert($penjualan);
    }
}