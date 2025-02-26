<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kategori = [
            [
                'nama_kategori' => 'Elektronik',
                'deskripsi_kategori' => 'Peralatan elektronik'
            ],
            [
                'nama_kategori' => 'Pakaian',
                'deskripsi_kategori' => 'Pakaian pria dan wanita'
            ],
            [
                'nama_kategori' => 'Makanan',
                'deskripsi_kategori' => 'Makanan dan minuman'
            ],
            [
                'nama_kategori' => 'Perabot',
                'deskripsi_kategori' => 'Perabot rumah tangga'
            ],
            [
                'nama_kategori' => 'Buku',
                'deskripsi_kategori' => 'Buku fiksi dan non-fiksi'
            ],
        ];

        DB::table('m_kategori')->insert($kategori);
    }
}