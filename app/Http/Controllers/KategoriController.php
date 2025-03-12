<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;

class KategoriController extends Controller
{
    public function index()
    {
        // Menampilkan halaman awal kategori
        $breadcrumb = (object) [
            'title' => 'Daftar Kategori', // Ganti judul
            'list' => ['Home', 'Kategori'] // Ganti breadcrumb
        ];

        $page = (object) [
            'title' => 'Daftar kategori yang terdaftar dalam sistem' // Ganti judul
        ];

        $activeMenu = 'kategori'; // Ganti menu aktif

        return view('kategori.index', [ // Ganti nama view
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list()
    {
        $kategori = KategoriModel::select('id', 'nama_kategori', 'deskripsi_kategori'); // Ganti kolom yang dipilih

        return DataTables::of($kategori)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kategori) {
                $btn = '<a href="' . url('/kategori/' . $kategori->id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/kategori/' . $kategori->id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' .
                    url('/kategori/' . $kategori->id) . '">' // Ganti URL
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Kategori', // Ganti judul
            'list' => ['Home', 'Kategori', 'Tambah'] // Ganti breadcrumb
        ];

        $page = (object) [
            'title' => 'Tambah kategori baru' // Ganti judul
        ];

        $activeMenu = 'kategori'; // Ganti menu aktif

        return view('kategori.create', [ // Ganti nama view
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:m_kategori,nama_kategori', // Ganti validasi
            'deskripsi_kategori' => 'required|string', // Ganti validasi
        ]);

        KategoriModel::create([ // Ganti model
            'nama_kategori' => $request->nama_kategori, // Ganti nama kolom
            'deskripsi_kategori' => $request->deskripsi_kategori, // Ganti nama kolom
        ]);

        return redirect('/kategori')->with('success', 'Data kategori berhasil disimpan'); // Ganti pesan dan URL
    }

    public function show(string $id)
    {
        $kategori = KategoriModel::find($id); // Ganti model

        $breadcrumb = (object) [
            'title' => 'Detail Kategori', // Ganti judul
            'list' => ['Home', 'Kategori', 'Detail'] // Ganti breadcrumb
        ];

        $page = (object) [
            'title' => 'Detail kategori' // Ganti judul
        ];

        $activeMenu = 'kategori'; // Ganti menu aktif

        return view('kategori.show', [ // Ganti nama view
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori, // Ganti variabel
            'activeMenu' => $activeMenu
        ]);
    }

    public function edit(string $id)
    {
        $kategori = KategoriModel::find($id); // Ganti model

        $breadcrumb = (object) [
            'title' => 'Edit Kategori', // Ganti judul
            'list' => ['Home', 'Kategori', 'Edit'] // Ganti breadcrumb
        ];

        $page = (object) [
            'title' => 'Edit kategori' // Ganti judul
        ];

        $activeMenu = 'kategori'; // Ganti menu aktif

        return view('kategori.edit', [ // Ganti nama view
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori, // Ganti variabel
            'activeMenu' => $activeMenu
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:m_kategori,nama_kategori,' . $id . ',id', // Ganti validasi
            'deskripsi_kategori' => 'required|string', // Ganti validasi
        ]);

        $kategori = KategoriModel::where('id', $id)->first(); // Ganti model
        $kategori->update([
            'nama_kategori' => $request->nama_kategori, // Ganti nama kolom
            'deskripsi_kategori' => $request->deskripsi_kategori, // Ganti nama kolom
        ]);

        return redirect('/kategori')->with('success', 'Data kategori berhasil diubah'); // Ganti pesan dan URL
    }

    public function destroy(string $id)
    {
        $check = KategoriModel::find($id); // Ganti model

        if (!$check) {
            return redirect('/kategori')->with('error', 'Data kategori tidak ditemukan'); // Ganti pesan dan URL
        }

        try {
            KategoriModel::destroy($id); // Ganti model
            return redirect('/kategori')->with('success', 'Data kategori berhasil dihapus'); // Ganti pesan dan URL
        } catch (QueryException $e) {
            return redirect('/kategori')->with('error', 'Data kategori gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini'); // Ganti pesan dan URL
        }
    }
}


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
    
        // $data = DB::table('m_kategori')->get();
        // return view('kategori', compact('data'));