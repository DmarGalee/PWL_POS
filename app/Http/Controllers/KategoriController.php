<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    public function index()
    {
        /*
       $data = [
            'nama_kategori' => 'SNK',
            'deskripsi_kategori' => 'Snack/Makanan Ringan',
            'created_at' => now(), // created_at akan diisi otomatis
            'updated_at' => now(), // updated_at akan diisi otomatis
        ];

        DB::table('m_kategori')->insert($data);
        return 'Insert data baru berhasil';
        */

        // $row = DB::table('m_kategori')->where('nama_kategori', 'SNK')->update(['nama_kategori' => 'Camilan']);
        // return 'Update data berhasil. Jumlah data yang diupdate: ' . $row . ' baris';

        //$row = DB::table('m_kategori')->where('nama_kategori', 'SNK')->delete();
        //return 'Delete data berhasil. Jumlah data yang dihapus: ' . $row . ' baris';
    
        $data = DB::table('m_kategori')->get();
        return view('kategori', compact('data'));
    }
}