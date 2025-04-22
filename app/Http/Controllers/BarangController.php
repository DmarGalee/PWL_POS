<?php
 
 namespace App\Http\Controllers;
 
 use App\Models\BarangModel;
 use App\Models\KategoriMode;
 use App\Models\KategoriModel;
 use App\Models\SupplierModel;
 use Illuminate\Http\Request;
 use Barryvdh\DomPDF\Facade\Pdf;
 use Illuminate\Support\Facades\Hash;
 use Illuminate\Database\QueryException;
 use Illuminate\Support\Facades\Validator;
 use PhpOffice\PhpSpreadsheet\IOFactory;
 use Yajra\DataTables\Facades\DataTables;
 
 class BarangController extends Controller
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
 
         $this->activeMenu = 'barang';
     }
 
     public function index()
     {
         $this->breadcrumb->title = 'Daftar Barang';
         $this->breadcrumb->list = ['Home', 'Barang'];
         $this->page->title = 'Daftar barang yang terdaftar dalam sistem';
 
         $kategori = KategoriModel::all(); // Ambil semua kategori

         return view('barang.index', [
             'breadcrumb' => $this->breadcrumb,
             'page' => $this->page,
             'activeMenu' => $this->activeMenu,
             'kategori' => $kategori
         ]);
     }
 
    public function list(Request $request)
{
    // Start the query for Barang with eager loading of relationships
    $barang = BarangModel::with('kategori', 'supplier')
        ->select(
            'm_barang.id',
            'm_barang.nama_barang',
            'm_barang.deskripsi_barang',
            'm_barang.harga_barang',
            'm_barang.id_kategori',
            'm_barang.id_supplier'
        );

    // Filter by category if the filter_kategori parameter is provided
    if ($request->has('filter_kategori') && $request->filter_kategori) {
        $barang->where('id_kategori', $request->filter_kategori);
    }

    // Use DataTables to return the processed data
    return DataTables::of($barang)
        ->addIndexColumn() // Adds an index column
        ->addColumn('nama_kategori', function ($barang) {
            return optional($barang->kategori)->nama_kategori; // Safely access the category name
        })
        ->addColumn('nama_supplier', function ($barang) {
            return optional($barang->supplier)->nama_supplier; // Safely access the supplier name
        })
        ->addColumn('aksi', function ($barang) {
            // Buttons for actions: detail, edit, delete
            $btn = '<button onclick="modalAction(\'' . url('/barang/' . $barang->id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
            $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
            $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
            return $btn;
        })
        ->rawColumns(['aksi']) // Ensure HTML in the action column is rendered
        ->make(true); // Return the DataTables response
}

     
 
     public function create()
     {
         $this->breadcrumb->title = 'Tambah Barang';
         $this->breadcrumb->list = ['Home', 'Barang', 'Tambah'];
         $this->page->title = 'Tambah barang baru';
 
         return view('barang.create', [
             'breadcrumb' => $this->breadcrumb,
             'page' => $this->page,
             'activeMenu' => $this->activeMenu
         ]);
     }
 
     public function store(Request $request)
     {
         $request->validate([
             'nama_barang' => 'required|string|max:100|unique:m_barang,nama_barang',
             'deskripsi_barang' => 'required|string',
             'harga_barang' => 'required|numeric',
             'id_kategori' => 'required|integer',
             'id_supplier' => 'required|integer',
         ]);
 
         BarangModel::create([
             'nama_barang' => $request->nama_barang,
             'deskripsi_barang' => $request->deskripsi_barang,
             'harga_barang' => $request->harga_barang,
             'id_kategori' => $request->id_kategori,
             'id_supplier' => $request->id_supplier,
         ]);
 
         return redirect('/barang')->with('success', 'Data barang berhasil disimpan');
     }
 
     public function show(string $id)
     {
         $barang = BarangModel::find($id);
 
         $this->breadcrumb->title = 'Detail Barang';
         $this->breadcrumb->list = ['Home', 'Barang', 'Detail'];
         $this->page->title = 'Detail barang';
 
         return view('barang.show', [
             'breadcrumb' => $this->breadcrumb,
             'page' => $this->page,
             'barang' => $barang,
             'activeMenu' => $this->activeMenu
         ]);
     }
 
     public function edit(string $id)
     {
         $barang = BarangModel::find($id);
 
         $this->breadcrumb->title = 'Edit Barang';
         $this->breadcrumb->list = ['Home', 'Barang', 'Edit'];
         $this->page->title = 'Edit barang';
 
         return view('barang.edit', [
             'breadcrumb' => $this->breadcrumb,
             'page' => $this->page,
             'barang' => $barang,
             'activeMenu' => $this->activeMenu
         ]);
     }
 
     public function update(Request $request, string $id)
     {
         $request->validate([
             'nama_barang' => 'required|string|max:100|unique:m_barang,nama_barang,' . $id . ',id',
             'deskripsi_barang' => 'required|string',
             'harga_barang' => 'required|numeric',
             'id_kategori' => 'required|integer',
             'id_supplier' => 'required|integer',
         ]);
 
         $barang = BarangModel::where('id', $id)->first();
         $barang->update([
             'nama_barang' => $request->nama_barang,
             'deskripsi_barang' => $request->deskripsi_barang,
             'harga_barang' => $request->harga_barang,
             'id_kategori' => $request->id_kategori,
             'id_supplier' => $request->id_supplier,
         ]);
 
         return redirect('/barang')->with('success', 'Data barang berhasil diubah');
     }
 
     public function destroy(string $id)
     {
         $check = BarangModel::find($id);
 
         if (!$check) {
             return redirect('/barang')->with('error', 'Data barang tidak ditemukan');
         }
 
         try {
             BarangModel::destroy($id);
             return redirect('/barang')->with('success', 'Data barang berhasil dihapus');
         } catch (QueryException $e) {
             return redirect('/barang')->with('error', 'Data barang gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
         }
     }

     public function create_ajax()
    {
        $kategori = KategoriModel::all();
        $supplier = SupplierModel::all();
        return view('barang.create_ajax', compact('kategori', 'supplier'));
    }

    // Menyimpan barang baru (AJAX)
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_barang' => 'required|string|max:100',
                'deskripsi_barang' => 'required|string',
                'harga_barang' => 'required|numeric',
                'id_kategori' => 'required|exists:m_kategori,id',
                'id_supplier' => 'required|exists:m_supplier,id',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ], 422);
            }

            BarangModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data barang berhasil disimpan',
            ]);
        }

        return redirect('/');
    }

    public function edit_ajax(string $id)
    {
        $barang = BarangModel::findOrFail($id);
        $kategori = KategoriModel::all();
        $supplier = SupplierModel::all();

        return view('barang.edit_ajax', compact('barang', 'kategori', 'supplier'));
    }

    // Menyimpan perubahan data barang (AJAX)
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_barang' => 'required|string|max:100',
                'deskripsi_barang' => 'required|string',
                'harga_barang' => 'required|numeric',
                'id_kategori' => 'required|exists:m_kategori,id',
                'id_supplier' => 'required|exists:m_supplier,id',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $barang = BarangModel::find($id);

            if ($barang) {
                $barang->update($request->all());
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

        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $barang = BarangModel::find($id);
        return view('barang.confirm_ajax', compact('barang'));
    }

    // Menghapus data barang (AJAX)
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $barang = BarangModel::find($id);

            if ($barang) {
                $barang->delete();
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

        return redirect('/');
    }

    public function import()
    {
    return view('barang.import');
    }

    public function import_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            // validasi file harus xls atau xlsx, max 1MB
            'file_barang' => ['required', 'mimes:xlsx', 'max:1024']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }
        $file = $request->file('file_barang'); // ambil file dari request
        $reader = IOFactory::createReader('Xlsx'); // load reader file excel
        $reader->setReadDataOnly(true); // hanya membaca data
        $spreadsheet = $reader->load($file->getRealPath()); // load file excel
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
        $data = $sheet->toArray(null, false, true, true); // ambil data excel
        $insert = [];

        if (count($data) > 1) { // jika data lebih dari 1 baris
            foreach ($data as $baris => $value) {
                if ($baris > 1) {  // Skip header (baris 1)
                    $insert[] = [
                        'nama_barang' => $value['B'] ?? null,  // Kolom B = Nama Barang
                        'deskripsi_barang' => $value['C'] ?? null,  // Kolom C = Deskripsi
                        'harga_barang' => $value['D'] ?? 0,  // Kolom D = Harga (default 0 jika kosong)
                        'id_kategori' => $value['E'] ?? null,  // Kolom E = ID Kategori
                        'id_supplier' => $value['F'] ?? null,  // Kolom F = ID Supplier
                        'created_at' => now(),
                    ];
                }
            }
            dd($insert);  // Tambahkan ini untuk melihat isi array $insert sebelum dimasukkan ke database
            if (count($insert) > 0) {
                // insert data ke database, jika data sudah ada, maka diabaikan
                BarangModel::insert($insert);
            }
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diimport'
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
    // 1. Ambil data dari database
    $barang = BarangModel::select("id", "nama_barang", "deskripsi_barang", "harga_barang", "id_kategori", "id_supplier")
        ->orderBy("id_kategori")
        ->with("kategori", "supplier")
        ->get();

    // 2. Inisialisasi pengaturan awal untuk file Excel (contoh menggunakan PhpSpreadsheet)
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Nama Barang');
    $sheet->setCellValue('C1', 'Deskripsi');
    $sheet->setCellValue('D1', 'Harga');
    $sheet->setCellValue('E1', 'ID Kategori');
    $sheet->setCellValue('F1', 'ID Supplier');
    $sheet->setCellValue('G1', 'Nama Kategori'); // Header untuk kolom Nama Kategori

    // 3. Isi data ke dalam sheet Excel
    $no = 1;
    $baris = 2;

    foreach ($barang as $key => $value) {
        $sheet->setCellValue('A' . $baris, $no);
        $sheet->setCellValue('B' . $baris, $value->nama_barang);
        $sheet->setCellValue('C' . $baris, $value->deskripsi_barang);
        $sheet->setCellValue('D' . $baris, $value->harga_barang);
        $sheet->setCellValue('E' . $baris, $value->id_kategori);
        $sheet->setCellValue('F' . $baris, $value->id_supplier);
        $sheet->setCellValue('G' . $baris, $value->kategori->nama_kategori); // Isi Nama Kategori

        $baris++;
        $no++;
    }

    foreach(range('A','F') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);

    }
    $sheet->setTitle('Data Barang');  // set title sheet
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $filename = 'Data Barang ' . date('Y-m-d H:i:s') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');

    $writer->save('php://output');
    exit;
}

public function export_pdf()
{
    // Ambil data barang yang akan di-export
    $barang = BarangModel::select('id', 'nama_barang', 'deskripsi_barang', 'harga_barang', 'id_kategori', 'id_supplier')
        ->orderBy('id_kategori')
        ->orderBy('id') // Diurutkan berdasarkan ID barang
        ->with('kategori', 'supplier') // Memuat relasi kategori dan supplier
        ->get();

    // use Barryvdh\DomPDF\Facade\Pdf;  // Pastikan ini tidak di-comment
    $pdf = Pdf::loadView('barang.export_pdf', ['barang' => $barang]);
    $pdf->setPaper('a4', 'portrait');  // Set ukuran kertas dan orientasi
    $pdf->setOption('isRemoteEnabled', true); // Set true jika ada gambar dari URL
    $pdf->render();
    return $pdf->stream('Data Barang ' . date('Y-m-d H:i:s') . '.pdf');
}

} 