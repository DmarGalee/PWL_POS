<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supplier = [
            [
                'nama_supplier' => 'PT Elektronik Jaya',
                'alamat_supplier' => 'Jakarta',
                'telepon_supplier' => '021-1234567'
            ],
            [
                'nama_supplier' => 'CV Pakaian Indah',
                'alamat_supplier' => 'Bandung',
                'telepon_supplier' => '022-9876543'
            ],
            [
                'nama_supplier' => 'UD Makanan Sehat',
                'alamat_supplier' => 'Surabaya',
                'telepon_supplier' => '031-1122334'
            ],
        ];

        DB::table('m_supplier')->insert($supplier);
    }
}