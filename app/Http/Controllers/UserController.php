<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // 🔍 Cek jika username 'manager11' sudah ada, jika tidak, buat baru
        $user = UserModel::updateOrCreate(
            ['username' => 'manager11'],
            [
                'nama_lengkap' => 'Manager11',
                'password' => Hash::make('12345'),
                'level_id' => 2,
            ]
        );

        // 🔍 Pastikan username baru 'manager12' belum ada sebelum update
        if (!UserModel::where('username', 'manager12')->exists()) {
            $user->username = 'manager12';
            $user->save();
        }

        // ✅ Cek apakah perubahan terjadi
        $wasChanged = $user->wasChanged(['nama_lengkap', 'username']);

        return view('user', ['data' => $user, 'wasChanged' => $wasChanged]);
    }
}
