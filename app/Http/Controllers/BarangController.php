<?php
 
 namespace App\Http\Controllers;
 
 use App\Models\BarangModel;
 use App\Models\KategoriMode;
 use App\Models\KategoriModel;
 use App\Models\SupplierModel;
 use Illuminate\Http\Request;
 use Illuminate\Database\QueryException;
 use Illuminate\Support\Facades\Validator;
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
         $barang = BarangModel::with('kategori', 'supplier')
             ->select(
                 'm_barang.id',
                 'm_barang.nama_barang',
                 'm_barang.deskripsi_barang',
                 'm_barang.harga_barang',
                 'm_barang.id_kategori',
                 'm_barang.id_supplier'
             );
     
         // Filter berdasarkan kategori jika ada
         if ($request->id_kategori) {
             $barang->where('id_kategori', $request->id_kategori);
         }
     
         return DataTables::of($barang)
             ->addIndexColumn()
             ->addColumn('nama_kategori', function ($barang) {
                 return optional($barang->kategori)->nama_kategori;
             })
             ->addColumn('nama_supplier', function ($barang) {
                 return optional($barang->supplier)->nama_supplier;
             })
             ->addColumn('aksi', function ($barang) {
                 $btn = '<button onclick="modalAction(\'' . url('/barang/' . $barang->id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                 $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                 $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                 return $btn;
             })
             ->rawColumns(['aksi'])
             ->make(true);
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
 }