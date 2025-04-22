<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;


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

    // public function list()
    // {
    //     $kategori = KategoriModel::select('id', 'nama_kategori', 'deskripsi_kategori'); // Ganti kolom yang dipilih

    //     return DataTables::of($kategori)
    //         ->addIndexColumn()
    //         ->addColumn('aksi', function ($kategori) {
    //             $btn = '<a href="' . url('/kategori/' . $kategori->id) . '" class="btn btn-info btn-sm">Detail</a> ';
    //             $btn .= '<a href="' . url('/kategori/' . $kategori->id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
    //             $btn .= '<form class="d-inline-block" method="POST" action="' .
    //                 url('/kategori/' . $kategori->id) . '">' // Ganti URL
    //                 . csrf_field() . method_field('DELETE') .
    //                 '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
    //             return $btn;
    //         })
    //         ->rawColumns(['aksi'])
    //         ->make(true);
    // }

    public function list(Request $request)
    {
        $kategori = KategoriModel::select('id', 'nama_kategori', 'deskripsi_kategori'); // Sesuaikan kolom

        return DataTables::eloquent($kategori)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kategori) {
                $btn = '<button onclick="modalAction(\''.url('/kategori/' . $kategori->id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/kategori/' . $kategori->id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/kategori/' . $kategori->id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
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

    public function create_ajax()
    {
        return view('kategori.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_kategori' => 'required|string|max:100|unique:m_kategori,nama_kategori',
                'deskripsi_kategori' => 'required|string',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ], 422);
            }

            KategoriModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data kategori berhasil disimpan',
            ]);
        }

        return redirect('/kategori'); // Sesuaikan redirect
    }

    public function edit_ajax(string $id)
    {
        $kategori = KategoriModel::findOrFail($id);
        return view('kategori.edit_ajax', compact('kategori'));
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_kategori' => 'required|string|max:100|unique:m_kategori,nama_kategori,' . $id . ',id',
                'deskripsi_kategori' => 'required|string',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $kategori = KategoriModel::find($id);

            if ($kategori) {
                $kategori->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }

        return redirect('/kategori'); // Sesuaikan redirect
    }

    public function confirm_ajax(string $id)
    {
        $kategori = KategoriModel::find($id);
        return view('kategori.confirm_ajax', compact('kategori'));
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $kategori = KategoriModel::find($id);

            if ($kategori) {
                $kategori->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan',
                ], 404);
            }
        }

        return redirect('/kategori'); // Sesuaikan redirect
    }

    public function import()
{
    return view('kategori.import'); // Pastikan view ini ada
}

public function import_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'file_kategori' => ['required', 'mimes:xlsx', 'max:1024']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $file = $request->file('file_kategori');
        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, false, true, true);

        $insert = [];

        if (count($data) > 1) {
            foreach ($data as $baris => $value) {
                if ($baris > 1) {
                    $insert[] = [
                        'nama_kategori' => $value['B'] ?? null, // Kolom B = Nama Kategori
                        'deskripsi_kategori' => $value['C'] ?? null, // Kolom C = Deskripsi
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // dd($insert); // Debugging opsional

            if (count($insert) > 0) {
                KategoriModel::insert($insert);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data kategori berhasil diimport'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data yang diimport'
            ]);
        }
    }

    return redirect('/');
}

public function export_excel()
{
    $kategori = KategoriModel::select("id", "nama_kategori", "deskripsi_kategori")
        ->orderBy("id")
        ->get();

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Nama Kategori');
    $sheet->setCellValue('C1', 'Deskripsi');

    $no = 1;
    $baris = 2;
    foreach ($kategori as $value) {
        $sheet->setCellValue('A' . $baris, $no++);
        $sheet->setCellValue('B' . $baris, $value->nama_kategori);
        $sheet->setCellValue('C' . $baris, $value->deskripsi_kategori);
        $baris++;
    }

    foreach (range('A', 'C') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    $sheet->setTitle('Data Kategori');
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $filename = 'Data Kategori ' . date('Y-m-d H-i-s') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');

    $writer->save('php://output');
    exit;
}

public function export_pdf()
{
    $kategori = KategoriModel::select('id', 'nama_kategori', 'deskripsi_kategori')
        ->orderBy('id')
        ->get();

    $pdf = Pdf::loadView('kategori.export_pdf', ['kategori' => $kategori]);
    $pdf->setPaper('a4', 'portrait');
    $pdf->setOption('isRemoteEnabled', true);
    $pdf->render();

    return $pdf->stream('Data Kategori ' . date('Y-m-d H:i:s') . '.pdf');
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

        