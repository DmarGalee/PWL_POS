<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Update data user dengan Eloquent Model
        $data = [
            'nama_lengkap' => 'Pelanggan Pertama',
            'updated_at' => now(), // Update updated_at
        ];

        UserModel::where('username', 'customer-1')->update($data); // Update data user

        // Coba akses model UserModel
        $user = UserModel::all(); // Ambil semua data dari tabel m_user

        return view('user', ['data' => $user]);
    }
}