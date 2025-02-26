<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stok = [];
        $barangIds = DB::table('m_barang')->pluck('id')->toArray();

        foreach ($barangIds as $barangId) {
            $stok[] = [
                'id_barang' => $barangId,
                'jumlah_stok' => rand(10, 100), // Jumlah stok acak antara 10 dan 100
            ];
        }

        DB::table('t_stok')->insert($stok);
    }
}