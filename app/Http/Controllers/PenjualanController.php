<?php

namespace App\Http\Controllers;

use App\Models\PenjualanModel;
use App\Models\UserModel;
use App\Models\BarangModel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class PenjualanController extends Controller
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

        $this->activeMenu = 'penjualan';
    }

    public function index()
    {
        $this->breadcrumb->title = 'Daftar Penjualan';
        $this->breadcrumb->list = ['Home', 'Penjualan'];
        $this->page->title = 'Daftar transaksi penjualan dalam sistem';

        $users = UserModel::all();

        return view('penjualan.index', [
            'breadcrumb' => $this->breadcrumb,
            'page' => $this->page,
            'activeMenu' => $this->activeMenu,
            'users' => $users
        ]);
    }
    
    public function getPenjualanList(Request $request)
    {
        $penjualan = PenjualanModel::with('user')
            ->periode($request->start_date, $request->end_date)
            ->when($request->user_id, function ($query) use ($request) {
                return $query->where('id_user', $request->user_id);
            })
            ->get();

        // Memformat data untuk DataTables
        $data = $penjualan->map(function ($item) {
            return [
                'id' => $item->id,
                'no_penjualan' => $item->nomor_penjualan,
                'tanggal_penjualan' => $item->tanggal_penjualan,
                'user' => $item->user->nama_lengkap ?? '-', // Mengakses nama_lengkap dengan aman
                'total_item' => $item->total_item,  // Menggunakan accessor
                'total_harga' => $item->total_harga, // Menggunakan accessor
                'aksi' => '<button onclick="modalAction(\'' . url('/penjualan/' . $item->id) . '\')" class="btn btn-sm btn-info">Detail</button>',
            ];
        });

        return DataTables::of($data)->make(true);
    }

    public function list(Request $request)
{
    $penjualan = PenjualanModel::with(['user', 'details.barang'])
        ->select(
            't_penjualan.id',
            't_penjualan.no_penjualan',
            't_penjualan.tanggal_penjualan',
            't_penjualan.id_user'
        )
        ->when($request->user_id, function ($query) use ($request) {
            return $query->where('id_user', $request->user_id);
        })
        ->when($request->start_date && $request->end_date, function ($query) use ($request) {
            return $query->whereBetween('tanggal_penjualan', [
                $request->start_date, 
                $request->end_date
            ]);
        });

    return DataTables::eloquent($penjualan)
        ->addIndexColumn()
        ->addColumn('nomor_penjualan', function ($penjualan) {
            return $penjualan->nomor_penjualan; // Menggunakan accessor
        })
        ->addColumn('tanggal', function ($penjualan) {
            return $penjualan->tanggal_penjualan->format('d/m/Y');
        })
        ->addColumn('nama_user', function ($penjualan) {
            return $penjualan->user->nama_lengkap ?? '-';
        })
        ->addColumn('total_item', function ($penjualan) {
            return $penjualan->details->sum('jumlah_barang');
        })
        ->addColumn('total_harga', function ($penjualan) {
            return 'Rp ' . number_format($penjualan->details->sum(function($detail) {
                return $detail->jumlah_barang * $detail->harga_satuan;
            }), 0, ',', '.');
        })
        ->addColumn('aksi', function ($penjualan) {
            $btn = '<button onclick="modalAction(\''.url('/penjualan/'.$penjualan->id.'/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
            $btn .= '<button onclick="modalAction(\''.url('/penjualan/'.$penjualan->id.'/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
            $btn .= '<button onclick="modalAction(\''.url('/penjualan/'.$penjualan->id.'/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button>';
            return $btn;
        })
        ->rawColumns(['aksi'])
        ->make(true);
}
    public function create()
    {
        $this->breadcrumb->title = 'Tambah Penjualan';
        $this->breadcrumb->list = ['Home', 'Penjualan', 'Tambah'];
        $this->page->title = 'Tambah transaksi penjualan baru';

        $users = UserModel::all();

        return view('penjualan.create', [
            'breadcrumb' => $this->breadcrumb,
            'page' => $this->page,
            'activeMenu' => $this->activeMenu,
            'users' => $users
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_penjualan' => 'required|string|unique:t_penjualan,no_penjualan',
            'tanggal_penjualan' => 'required|date',
            'id_user' => 'required|integer|exists:m_user,user_id',
        ]);

        PenjualanModel::create([
            'no_penjualan' => $request->no_penjualan,
            'tanggal_penjualan' => $request->tanggal_penjualan,
            'id_user' => $request->id_user,
        ]);

        return redirect('/penjualan')->with('success', 'Data penjualan berhasil disimpan');
    }

    public function show(string $id)
    {
        $penjualan = PenjualanModel::with('user')->find($id);

        $this->breadcrumb->title = 'Detail Penjualan';
        $this->breadcrumb->list = ['Home', 'Penjualan', 'Detail'];
        $this->page->title = 'Detail transaksi penjualan';

        return view('penjualan.show', [
            'breadcrumb' => $this->breadcrumb,
            'page' => $this->page,
            'penjualan' => $penjualan,
            'activeMenu' => $this->activeMenu
        ]);
    }

    public function edit(string $id)
    {
        $penjualan = PenjualanModel::find($id);
        $users = UserModel::all();

        $this->breadcrumb->title = 'Edit Penjualan';
        $this->breadcrumb->list = ['Home', 'Penjualan', 'Edit'];
        $this->page->title = 'Edit transaksi penjualan';

        return view('penjualan.edit', [
            'breadcrumb' => $this->breadcrumb,
            'page' => $this->page,
            'penjualan' => $penjualan,
            'users' => $users,
            'activeMenu' => $this->activeMenu
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'no_penjualan' => 'required|string|unique:t_penjualan,no_penjualan,'.$id.',id',
            'tanggal_penjualan' => 'required|date',
            'id_user' => 'required|integer|exists:m_user,user_id',
        ]);

        $penjualan = PenjualanModel::find($id);
        $penjualan->update([
            'no_penjualan' => $request->no_penjualan,
            'tanggal_penjualan' => $request->tanggal_penjualan,
            'id_user' => $request->id_user,
        ]);

        return redirect('/penjualan')->with('success', 'Data penjualan berhasil diubah');
    }

    public function destroy(string $id)
    {
        $penjualan = PenjualanModel::find($id);
        
        if (!$penjualan) {
            return redirect('/penjualan')->with('error', 'Data penjualan tidak ditemukan');
        }

        try {
            $penjualan->delete();
            return redirect('/penjualan')->with('success', 'Data penjualan berhasil dihapus');
        } catch (QueryException $e) {
            return redirect('/penjualan')->with('error', 'Data penjualan gagal dihapus karena masih terdapat data terkait');
        }
    }

    public function create_ajax()
    {
        $users = UserModel::all(); // Ambil data user untuk dropdown
        $barang = BarangModel::all(); // Ambil data barang untuk dropdown
        return view('penjualan.create_ajax', compact('users', 'barang')); // Tambahkan 'barang' ke compact
    }
    

public function store_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'no_penjualan' => 'required|string|unique:t_penjualan,no_penjualan',
            'tanggal_penjualan' => 'required|date',
            'id_user' => 'required|integer|exists:m_user,user_id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ], 422);
        }

        PenjualanModel::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Data penjualan berhasil disimpan',
        ]);
    }

    return redirect('/');
}

public function edit_ajax(string $id)
{
    $penjualan = PenjualanModel::findOrFail($id);
    $users = UserModel::all(); // Ambil data user untuk dropdown
    $barang = BarangModel::all(); // Ambil data barang
    return view('penjualan.edit_ajax', compact('penjualan', 'users', 'barang')); // Tambahkan $barang ke compact
}


public function update_ajax(Request $request, $id)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'no_penjualan' => 'required|string|unique:t_penjualan,no_penjualan,'.$id.',id',
            'tanggal_penjualan' => 'required|date',
            'id_user' => 'required|integer|exists:m_user,user_id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $penjualan = PenjualanModel::find($id);

        if ($penjualan) {
            $penjualan->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data penjualan berhasil diupdate'
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
    $penjualan = PenjualanModel::with('user')->find($id);
    return view('penjualan.confirm_ajax', compact('penjualan'));
}

public function delete_ajax(Request $request, $id)
{
    if ($request->ajax() || $request->wantsJson()) {
        $penjualan = PenjualanModel::find($id);

        if ($penjualan) {
            $penjualan->delete();
            return response()->json([
                'status' => true,
                'message' => 'Data penjualan berhasil dihapus',
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
    $users = UserModel::all(); // Untuk dropdown user/kasir
    return view('penjualan.import', compact('users')); 
}

public function import_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'file_penjualan' => ['required', 'mimes:xlsx', 'max:1024']
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $file = $request->file('file_penjualan');
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
                    $user = UserModel::where('user_id', $value['C'] ?? null)->first();
                    
                    if (!$user) {
                        $errorMessages[] = "Baris $baris: User dengan ID {$value['C']} tidak ditemukan";
                        continue;
                    }

                    $insert[] = [
                        'no_penjualan' => $value['B'] ?? 'PJ'.date('YmdHis').rand(100,999),
                        'tanggal_penjualan' => $value['D'] ?? date('Y-m-d'),
                        'id_user' => $value['C'] ?? null,
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
                    PenjualanModel::insert($insert);
                    return response()->json([
                        'status' => true,
                        'message' => 'Data penjualan berhasil diimport'
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
    try {
        $penjualan = PenjualanModel::with('user', 'PenjualanDetail')
            ->select("id", "no_penjualan", "tanggal_penjualan", "id_user")
            ->orderBy("tanggal_penjualan", "desc")
            ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nomor Penjualan');
        $sheet->setCellValue('C1', 'Tanggal Penjualan');
        $sheet->setCellValue('D1', 'Kasir');
        $sheet->setCellValue('E1', 'Total Item');
        $sheet->setCellValue('F1', 'Total Harga');

        // Data
        $no = 1;
        $baris = 2;
        foreach ($penjualan as $item) {
            $sheet->setCellValue('A' . $baris, $no++);
            $sheet->setCellValue('B' . $baris, $item->no_penjualan);
            $sheet->setCellValue('C' . $baris, $item->tanggal_penjualan);
            $sheet->setCellValue('D' . $baris, $item->user->nama_lengkap ?? '-');

            $total_item = 0;
            $total_harga = 0;
            if ($item->PenjualanDetail) {
                foreach ($item->PenjualanDetail as $detail) {
                    $total_item += $detail->jumlah_barang;
                    $total_harga += $detail->harga_satuan; // Perbaikan: menjumlahkan harga satuan
                }
            }
            $sheet->setCellValue('E' . $baris, $total_item);
            $sheet->setCellValue('F' . $baris, $total_harga);
            $baris++;
        }

        // Auto size columns
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Penjualan');
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Penjualan ' . date('Y-m-d H-i-s') . '.xlsx';

        // Headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    } catch (\Exception $e) {
        // Log error
        \Log::error('Error exporting to Excel: ' . $e->getMessage());
        // Tampilkan pesan error ke user
        return back()->with('error', 'Terjadi kesalahan saat mengekspor data ke Excel: ' . $e->getMessage());
    }
}



public function export_pdf()
{
    $penjualan = PenjualanModel::with(['user', 'PenjualanDetail'])
        ->orderBy('tanggal_penjualan', 'desc')
        ->get();

    $pdf = Pdf::loadView('penjualan.export_pdf', [
        'penjualan' => $penjualan,
        'title' => 'Laporan Data Penjualan',
        'date' => date('d/m/Y H:i:s')
    ]);
    
    $pdf->setPaper('a4', 'landscape');
    $pdf->setOption('isRemoteEnabled', true);
    $pdf->render();

    return $pdf->stream('Data Penjualan ' . date('Y-m-d H:i:s') . '.pdf');
}
}