<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $barang = [
            [
                'nama_barang' => 'Laptop ASUS ROG Strix G15',
                'deskripsi_barang' => 'Laptop gaming dengan spesifikasi tinggi.',
                'harga_barang' => rand(15000000, 25000000),
                'id_kategori' => rand(1, 5), // Asumsi ada 5 kategori
                'id_supplier' => rand(1, 3), // Asumsi ada 3 supplier
            ],
            [
                'nama_barang' => 'Kemeja Pria Lengan Panjang Cotton On',
                'deskripsi_barang' => 'Kemeja kasual untuk pria.',
                'harga_barang' => rand(200000, 500000),
                'id_kategori' => rand(1, 5),
                'id_supplier' => rand(1, 3),
            ],
            [
                'nama_barang' => 'Beras Premium Cap Ayam Jago 5kg',
                'deskripsi_barang' => 'Beras berkualitas tinggi.',
                'harga_barang' => rand(60000, 100000),
                'id_kategori' => rand(1, 5),
                'id_supplier' => rand(1, 3),
            ],
            [
                'nama_barang' => 'Sofa Minimalis 2 Seater IKEA',
                'deskripsi_barang' => 'Sofa untuk ruang tamu minimalis.',
                'harga_barang' => rand(1500000, 3000000),
                'id_kategori' => rand(1, 5),
                'id_supplier' => rand(1, 3),
            ],
            [
                'nama_barang' => 'Novel "Laskar Pelangi" Andrea Hirata',
                'deskripsi_barang' => 'Novel fiksi Indonesia populer.',
                'harga_barang' => rand(50000, 150000),
                'id_kategori' => rand(1, 5),
                'id_supplier' => rand(1, 3),
            ],
            [
                'nama_barang' => 'Smartphone Samsung Galaxy S23 Ultra',
                'deskripsi_barang' => 'Smartphone flagship dengan kamera canggih.',
                'harga_barang' => rand(15000000, 20000000),
                'id_kategori' => rand(1, 5),
                'id_supplier' => rand(1, 3),
            ],
            [
                'nama_barang' => 'Celana Jeans Wanita Levi\'s 501',
                'deskripsi_barang' => 'Celana jeans klasik untuk wanita.',
                'harga_barang' => rand(500000, 1000000),
                'id_kategori' => rand(1, 5),
                'id_supplier' => rand(1, 3),
            ],
            [
                'nama_barang' => 'Mie Instan Indomie Goreng 1 Dus',
                'deskripsi_barang' => 'Mie instan populer.',
                'harga_barang' => rand(100000, 200000),
                'id_kategori' => rand(1, 5),
                'id_supplier' => rand(1, 3),
            ],
            [
                'nama_barang' => 'Meja Kerja Kayu Jati',
                'deskripsi_barang' => 'Meja kerja kokoh dari kayu jati.',
                'harga_barang' => rand(1000000, 2500000),
                'id_kategori' => rand(1, 5),
                'id_supplier' => rand(1, 3),
            ],
            [
                'nama_barang' => 'Buku "Sapiens" Yuval Noah Harari',
                'deskripsi_barang' => 'Buku non-fiksi tentang sejarah manusia.',
                'harga_barang' => rand(80000, 200000),
                'id_kategori' => rand(1, 5),
                'id_supplier' => rand(1, 3),
            ],
            [
                'nama_barang' => 'Kamera Mirrorless Sony Alpha 7 III',
                'deskripsi_barang' => 'Kamera mirrorless profesional.',
                'harga_barang' => rand(18000000, 30000000),
                'id_kategori' => rand(1, 5),
                'id_supplier' => rand(1, 3),
            ],
            [
                'nama_barang' => 'Jaket Hoodie Pria Adidas',
                'deskripsi_barang' => 'Jaket hoodie sporty untuk pria.',
                'harga_barang' => rand(400000, 800000),
                'id_kategori' => rand(1, 5),
                'id_supplier' => rand(1, 3),
            ],
            [
                'nama_barang' => 'Air Mineral Aqua 1 Dus',
                'deskripsi_barang' => 'Air mineral kemasan.',
                'harga_barang' => rand(30000, 60000),
                'id_kategori' => rand(1, 5),
                'id_supplier' => rand(1, 3),
            ],
            [
                'nama_barang' => 'Lemari Pakaian 3 Pintu Olympic',
                'deskripsi_barang' => 'Lemari pakaian dengan banyak ruang penyimpanan.',
                'harga_barang' => rand(1200000, 2800000),
                'id_kategori' => rand(1, 5),
                'id_supplier' => rand(1, 3),
            ],
            [
                'nama_barang' => 'Buku "Harry Potter and the Sorcerer\'s Stone" J.K. Rowling',
                'deskripsi_barang' => 'Novel fantasi populer.',
                'harga_barang' => rand(70000, 180000),
                'id_kategori' => rand(1, 5),
                'id_supplier' => rand(1, 3),
            ],
        ];

        DB::table('m_barang')->insert($barang);
    }
}