<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $penjualanDetail = [];
        $penjualanIds = DB::table('t_penjualan')->pluck('id')->toArray();
        $barangIds = DB::table('m_barang')->pluck('id')->toArray();

        foreach ($penjualanIds as $penjualanId) {
            for ($i = 0; $i < 3; $i++) {
                $penjualanDetail[] = [
                    'id_penjualan' => $penjualanId,
                    'id_barang' => $barangIds[array_rand($barangIds)],
                    'jumlah_barang' => rand(1, 5),
                    'harga_satuan' => rand(10000, 100000),
                ];
            }
        }

        DB::table('t_penjualan_detail')->insert($penjualanDetail);
    }
}