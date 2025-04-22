<?php

namespace App\Http\Controllers;

use App\Models\StokModel;
use App\Models\BarangModel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class StokController extends Controller
{
    private $breadcrumb;
    private $page;
    private $activeMenu;

    public function __construct()
    {
        $this->breadcrumb = (object) [
            'title' => '',
            'list' => []
        ];

        $this->page = (object) [
            'title' => ''
        ];

        $this->activeMenu = 'stok';
    }

    public function index()
    {
        $this->breadcrumb->title = 'Daftar Stok';
        $this->breadcrumb->list = ['Home', 'Stok'];
        $this->page->title = 'Daftar stok barang dalam sistem';

        $barang = BarangModel::all();

        return view('stok.index', [
            'breadcrumb' => $this->breadcrumb,
            'page' => $this->page,
            'activeMenu' => $this->activeMenu,
            'barang' => $barang
        ]);
    }

    public function list(Request $request)
    {
        $stok = StokModel::select('id', 'id_barang', 'jumlah_stok');

        return DataTables::eloquent($stok)
            ->addIndexColumn()
            ->addColumn('aksi', function ($stok) {
                $btn = '<button onclick="modalAction(\'' . url('/stok/' . $stok->id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $stok->id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $stok->id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }


    public function create()
    {
        $this->breadcrumb->title = 'Tambah Stok';
        $this->breadcrumb->list = ['Home', 'Stok', 'Tambah'];
        $this->page->title = 'Tambah stok baru';

        return view('stok.create', [
            'breadcrumb' => $this->breadcrumb,
            'page' => $this->page,
            'activeMenu' => $this->activeMenu
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|integer|exists:m_barang,id',
            'stok' => 'required|integer|min:0',
        ]);

        StokModel::create([
            'id_barang' => $request->id_barang,
            'stok' => $request->stok,
        ]);

        return redirect('/stok')->with('success', 'Data stok berhasil disimpan');
    }

    public function show(string $id)
    {
        $stok = StokModel::find($id);

        $this->breadcrumb->title = 'Detail Stok';
        $this->breadcrumb->list = ['Home', 'Stok', 'Detail'];
        $this->page->title = 'Detail stok';

        return view('stok.show', [
            'breadcrumb' => $this->breadcrumb,
            'page' => $this->page,
            'stok' => $stok,
            'activeMenu' => $this->activeMenu
        ]);
    }

    public function edit(string $id)
    {
        $stok = StokModel::find($id);

        $this->breadcrumb->title = 'Edit Stok';
        $this->breadcrumb->list = ['Home', 'Stok', 'Edit'];
        $this->page->title = 'Edit stok';

        return view('stok.edit', [
            'breadcrumb' => $this->breadcrumb,
            'page' => $this->page,
            'stok' => $stok,
            'activeMenu' => $this->activeMenu
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_barang' => 'required|integer|exists:m_barang,id',
            'stok' => 'required|integer|min:0',
        ]);

        $stok = StokModel::where('id', $id)->first();
        $stok->update([
            'id_barang' => $request->id_barang,
            'stok' => $request->stok,
        ]);

        return redirect('/stok')->with('success', 'Data stok berhasil diubah');
    }

    public function destroy(string $id)
    {
        $check = StokModel::find($id);
        if (!$check) {
            return redirect('/stok')->with('error', 'Data stok tidak ditemukan');
        }

        try {
            StokModel::destroy($id);
            return redirect('/stok')->with('success', 'Data stok berhasil dihapus');
        } catch (QueryException $e) {
            return redirect('/stok')->with('error', 'Data stok gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function create_ajax()
{
    $barang = BarangModel::all(); // Ambil data barang untuk dropdown
    return view('stok.create_ajax', compact('barang'));
}

public function store_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'id_barang' => 'required|integer|exists:m_barang,id',
            'stok' => 'required|integer|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ], 422);
        }

        StokModel::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Data stok berhasil disimpan',
        ]);
    }

    return redirect('/');
}

public function edit_ajax(string $id)
{
    $stok = StokModel::findOrFail($id);
    $barang = BarangModel::all(); // Ambil data barang untuk dropdown
    return view('stok.edit_ajax', compact('stok', 'barang'));
}

public function update_ajax(Request $request, $id)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'id_barang' => 'required|integer|exists:m_barang,id',
            'stok' => 'required|integer|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $stok = StokModel::find($id);

        if ($stok) {
            $stok->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data stok berhasil diupdate'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    return redirect('/');
}

public function confirm_ajax(string $id)
{
    $stok = StokModel::with('barang')->find($id);
    return view('stok.confirm_ajax', compact('stok'));
}

public function delete_ajax(Request $request, $id)
{
    if ($request->ajax() || $request->wantsJson()) {
        $stok = StokModel::find($id);

        if ($stok) {
            $stok->delete();
            return response()->json([
                'status' => true,
                'message' => 'Data stok berhasil dihapus',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }
    }

    return redirect('/');
}

public function import()
{
    $barang = BarangModel::all(); // Untuk dropdown jika diperlukan di view
    return view('stok.import', compact('barang')); 
}

public function import_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'file_stok' => ['required', 'mimes:xlsx', 'max:1024']
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $file = $request->file('file_stok');
        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, false, true, true);

        $insert = [];
        $errorMessages = [];

        if (count($data) > 1) {
            foreach ($data as $baris => $value) {
                if ($baris > 1) {
                    // Validasi data sebelum dimasukkan
                    $barang = BarangModel::where('id', $value['B'] ?? null)->first();
                    
                    if (!$barang) {
                        $errorMessages[] = "Baris $baris: Barang dengan ID {$value['B']} tidak ditemukan";
                        continue;
                    }

                    $insert[] = [
                        'id_barang' => $value['B'] ?? null,
                        'stok' => $value['C'] ?? 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (!empty($errorMessages)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Beberapa data gagal diimport',
                    'errors' => $errorMessages
                ]);
            }

            if (count($insert) > 0) {
                try {
                    StokModel::insert($insert);
                    return response()->json([
                        'status' => true,
                        'message' => 'Data stok berhasil diimport'
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Gagal menyimpan data',
                        'error' => $e->getMessage()
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang valid untuk diimport'
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'File kosong atau format tidak sesuai'
            ]);
        }
    }

    return redirect('/');
}

public function export_excel()
{
    $stok = StokModel::with('barang')->select("id", "id_barang", "jumlah_stok") // Eager load relasi 'barang'
        ->orderBy("id")
        ->get();

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'ID Barang');
    $sheet->setCellValue('C1', 'Nama Barang');
    $sheet->setCellValue('D1', 'Jumlah Stok');

    // Data
    $no = 1;
    $baris = 2;
    foreach ($stok as $item) {
        $sheet->setCellValue('A' . $baris, $no++);
        $sheet->setCellValue('B' . $baris, $item->id_barang);
        // Access nama_barang melalui relasi yang sudah di-load
        $sheet->setCellValue('C' . $baris, $item->barang ? $item->barang->nama_barang : '-');
        $sheet->setCellValue('D' . $baris, $item->jumlah_stok); // Gunakan 'jumlah_stok'
        $baris++;
    }

    // Auto size columns
    foreach (range('A', 'D') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    $sheet->setTitle('Data Stok');
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $filename = 'Data Stok ' . date('Y-m-d H-i-s') . '.xlsx';

    // Headers for download
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
    $stok = StokModel::with('barang')
        ->select('id', 'id_barang', 'jumlah_stok')
        ->orderBy('id')
        ->get();

    $pdf = Pdf::loadView('stok.export_pdf', [
        'stok' => $stok,
        'title' => 'Laporan Data Stok',
        'date' => date('d/m/Y')
    ]);
    
    $pdf->setPaper('a4', 'portrait');
    $pdf->setOption('isRemoteEnabled', true);
    $pdf->render();

    return $pdf->stream('Data Stok ' . date('Y-m-d H:i:s') . '.pdf');
}
}