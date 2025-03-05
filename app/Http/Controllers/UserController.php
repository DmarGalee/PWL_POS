<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        $user = UserModel::with('level')->get();
        return view('user', ['data' => $user]);
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

}
