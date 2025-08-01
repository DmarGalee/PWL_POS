<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    public function index()
    {
        // Menampilkan halaman awal user
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif
        $level = LevelModel::all(); //ambil data level untuk filter level

        return view('user.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }

    // Praktikum dari minggu 5
//     public function list(Request $request)
// {
// $users = UserModel::select('user_id', 'username', 'nama_lengkap', 'level_id')
// ->with('level');

//     //filter data user berdasarkan level_id
//     if($request->level_id) {
//         $users->where('level_id', $request->level_id);
//     }

// return DataTables::of($users)
//  // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
// ->addIndexColumn()
// ->addColumn('aksi', function ($user) { // menambahkan kolom aksi
// $btn = '<a href="'.url('/user/' . $user->user_id).'" class="btn btn-info btnsm">Detail</a> ';
// $btn .= '<a href="'.url('/user/' . $user->user_id . '/edit').'" class="btn btnwarning btn-sm">Edit</a> ';
// $btn .= '<form class="d-inline-block" method="POST" action="'.
// url('/user/'.$user->user_id).'">'
// . csrf_field() . method_field('DELETE') .
// '<button type="submit" class="btn btn-danger btn-sm" onclick="return
// confirm(\'Apakah Anda yakit menghapus data ini?\');">Hapus</button></form>';
// return $btn;
// })
// ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
// ->make(true);
// }

//Praktikum minggu ke 6
// Ambil data user dalam bentuk json untuk datatables
public function list(Request $request){
    // Ambil data user dengan relasi level
    $users = UserModel::select('user_id', 'username', 'nama_lengkap', 'level_id')
->with('level');

    // Filter berdasarkan level_id jika ada
    if($request->level_id) {
        $users->where('level_id', $request->level_id);
    }

    return DataTables::eloquent($users)
        ->addIndexColumn() // Tambahkan kolom index otomatis
        ->addColumn('aksi', function ($user) {
            $btn = '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
            $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
            $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';

            return $btn;
        })
        ->rawColumns(['aksi']) // Pastikan kolom aksi bisa menampilkan HTML
        ->make(true);
}

 /* $btn = '<a href="'.url('/user/' . $user->user_id).'" class="btn btn-info btnsm">Detail</a> ';
                $btn .= '<a href="'.url('/user/' . $user->user_id . '/edit').'" class="btn btnwarning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/user/'.$user->user_id).'">'. csrf_field() . method_field('DELETE') .'<button type="submit" class="btn btn-danger btn-sm" onclick="return
                confirm(\'Apakah Anda yakit menghapus data ini?\');">Hapus</button></form>';*/

public function create()
{
    $breadcrumb = (object) [
        'title' => 'Tambah User',
        'list' => ['Home', 'User', 'Tambah']
    ];

    $page = (object) [
        'title' => 'Tambah user baru'
    ];

    $level = LevelModel::all(); // ambil data level untuk ditampilkan di form
    $activeMenu = 'user'; // set menu yang sedang aktif

    return view('user.create', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'level' => $level,
        'activeMenu' => $activeMenu
    ]);
}
    
public function store(Request $request)
{
    $request->validate([
        'username' => 'required|string|min:3|unique:m_user,username', // username harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_user kolom username
        'nama_lengkap' => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
        'password' => 'required|min:5', // password harus diisi dan minimal 5 karakter
        'level_id' => 'required|integer' // level_id harus diisi dan berupa angka
    ]);

    UserModel::create([
        'username' => $request->username,
        'nama_lengkap' => $request->nama_lengkap,
        'password' => bcrypt($request->password), // password dienkripsi sebelum disimpan
        'level_id' => $request->level_id
    ]);

    return redirect('/user')->with('success', 'Data user berhasil disimpan');
}
    //Menampilkan detail user
    public function show(string $id)
{
    $user = UserModel::with('level')->find($id);

    $breadcrumb = (object) [
        'title' => 'Detail User',
        'list' => ['Home', 'User', 'Detail']
    ];

    $page = (object) [
        'title' => 'Detail user'
    ];

    $activeMenu = 'user'; // set menu yang sedang aktif

    return view('user.show', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'user' => $user,
        'activeMenu' => $activeMenu
    ]);
}
    // Menampilkan halaman form edit user
public function edit(string $id)
{
    $user = UserModel::find($id);
    $level = LevelModel::all();

    $breadcrumb = (object) [
        'title' => 'Edit User',
        'list' => ['Home', 'User', 'Edit']
    ];

    $page = (object) [
        'title' => 'Edit user'
    ];

    $activeMenu = 'user'; // set menu yang sedang aktif

    return view('user.edit', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'user' => $user,
        'level' => $level,
        'activeMenu' => $activeMenu
    ]);
}

    // Menyimpan perubahan data user
public function update(Request $request, string $id)
{
    $request->validate([
        'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id', // username harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel users kolom username kecuali untuk user dengan id yang sedang diedit
        'nama_lengkap' => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
        'password' => 'nullable|min:5', // password bisa diisi (minimal 5 karakter) dan bisa tidak diisi
        'level_id' => 'required|integer' // level_id harus diisi dan berupa angka
    ]);

    $user = UserModel::where('user_id', $id)->first();
    $user->update([
        'username' => $request->username,
        'nama_lengkap' => $request->nama_lengkap,
        'password' => $request->password ? bcrypt($request->password) : $user->password,
        'level_id' => $request->level_id
    ]);

    return redirect('/user')->with('success', 'Data user berhasil diubah');
}

    // Menghapus data user
public function destroy(string $id)
{
    $check = UserModel::find($id);

    if (!$check) { // untuk mengecek apakah data user dengan id yang dimaksud ada atau tidak
        return redirect('/user')->with('error', 'Data user tidak ditemukan');
    }

    try {
        UserModel::destroy($id);
        return redirect('/user')->with('success', 'Data level berhasil dihapus');
    } catch (QueryException $e) {
        return redirect('/user')->with('error', 'Data level gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
    }
}

public function create_ajax()
{
    $level = LevelModel::select('level_id', 'level_nama')->get();

    return view('user.create_ajax')->with('level', $level);
}

public function store_ajax(Request $request)
{
    // Cek apakah request berupa AJAX atau JSON
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'level_id' => 'required|integer',
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama_lengkap' => 'required|string|max:100',
            'password' => 'required|min:6',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ], 422);
        }

        UserModel::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Data user berhasil disimpan',
        ]);
    }

    return redirect('/');
}
public function edit_ajax(string $id)
{
    $user = UserModel::findOrFail($id); // Otomatis return 404 jika tidak ditemukan
    $level = LevelModel::select('level_id', 'level_nama')->get();

    return view('user.edit_ajax',['user' => $user, 'level' => $level]);
}


public function update_ajax(Request $request, $id){
    // cek apakah request dari ajax
    if ($request->ajax() || $request->wantsJson()) {
    $rules = [
    'level_id' => 'required|integer',
    'username' => 'required|max:20|unique:m_user,username,'.$id.',user_id',
    'nama_lengkap' => 'required|max:100',
    'password' => 'nullable|min:6|max:20'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json([
        'status' => false, // respon json, true: berhasil, false: gagal
        'message' => 'Validasi gagal.',
        'msgField' => $validator->errors() // menunjukkan field mana yang error
        ]);
        }
        $check = UserModel::find($id);
        if ($check) {
        if(!$request->filled('password') ){ // jika password tidak diisi, maka hapus dari request
        $request->request->remove('password');
        }
        $check->update($request->all());
        return response()->json([
        'status' => true,
        'message' => 'Data berhasil diupdate'
        ]);
        } else{
        return response()->json([
        'status' => false,
        'message' => 'Data tidak ditemukan'
        ]);
        }
        
        }
        return redirect('/');
    }

    public function confirm_ajax(string $id){
        $user = UserModel::find($id);

        return view('user.confirm_ajax', ['user' => $user]);
    }

    public function delete_ajax(Request $request, $id)
{
    // Cek apakah request dari AJAX atau JSON
    if ($request->ajax() || $request->wantsJson()) {
        try {
            $user = UserModel::find($id);
            if ($user) {
                $user->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            } 
        } catch (\QueryException ) {
            return response()->json([
                'status' => false,
                'message' => 'Data gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini'
            ]);
        }
    }

    return redirect('/');
}

public function import()
{
    return view('user.import'); // Pastikan view user.import ada
}

public function import_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'file_user' => ['required', 'mimes:xlsx', 'max:1024']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $file = $request->file('file_user');
        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, false, true, true);

        $insert = [];

        if (count($data) > 1) {
            foreach ($data as $baris => $value) {
                if ($baris > 1) {
                    // Cari level_id berdasarkan kode level
                    $level = LevelModel::where('level_kode', $value['D'] ?? '')->first();
                    
                    $insert[] = [
                        'username' => $value['B'] ?? null,
                        'password' => bcrypt($value['C'] ?? 'password'), // Default password jika kosong
                        'nama_lengkap' => $value['E'] ?? null,
                        'level_id' => $level->level_id ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (count($insert) > 0) {
                UserModel::insert($insert);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil diimport'
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
    $users = UserModel::with('level')
                ->select("user_id", "username", "nama_lengkap", "level_id")
                ->orderBy("user_id")
                ->get();

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Username');
    $sheet->setCellValue('C1', 'Nama Lengkap');
    $sheet->setCellValue('D1', 'Level');

    // Data
    $no = 1;
    $baris = 2;
    foreach ($users as $user) {
        $sheet->setCellValue('A' . $baris, $no++);
        $sheet->setCellValue('B' . $baris, $user->username);
        $sheet->setCellValue('C' . $baris, $user->nama_lengkap);
        $sheet->setCellValue('D' . $baris, $user->level->level_nama ?? '-');
        $baris++;
    }

    // Auto size columns
    foreach (range('A', 'D') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    $sheet->setTitle('Data User');
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $filename = 'Data User ' . date('Y-m-d H-i-s') . '.xlsx';

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
    $users = UserModel::with('level')
                ->select('user_id', 'username', 'nama_lengkap', 'level_id')
                ->orderBy('user_id')
                ->get();

    $pdf = Pdf::loadView('user.export_pdf', ['users' => $users]);
    $pdf->setPaper('a4', 'portrait');
    $pdf->setOption('isRemoteEnabled', true);
    $pdf->render();

    return $pdf->stream('Data User ' . date('Y-m-d H:i:s') . '.pdf');
}
}



    // public function index()
    // {
    //     $user = UserModel::all();
    //     return view('user', ['data' => $user]);
    // }

//     public function tambah(){
//         return view('user_tambah');
//     }

//     public function tambah_simpan(Request $request){
//         UserModel::create([
//             'username' => $request->username,
//             'nama_lengkap' => $request->nama_lengkap,
//             'password' => Hash::make('$request->password'),
//             'level_id' => $request->level_id
//         ]);
//         return redirect('/user');
//     }

//     public function ubah($id){
//         $user = UserModel::find($id);
//         return view('user_ubah', ['data' => $user]);
//     }

//     public function ubah_simpan($id, Request $request)
//     {
//     $user = UserModel::find($id);

//     $user->username = $request->username;
//     $user->nama_lengkap = $request->nama_lengkap;
//     $user->password = Hash::make('$request->password');
//     $user->level_id = $request->level_id;

//     $user->save();

//     return redirect('/user');
//     }

//     public function hapus($id)
// {
//     $user = UserModel::find($id);

//     if (!$user) {
//         return redirect('/user')->with('error', 'User tidak ditemukan!');
//     }

//     $user->delete();

//     return redirect('/user')->with('success', 'User berhasil dihapus!');
// }