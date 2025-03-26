<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\UserModel;


class AuthController extends Controller
{

    
    public function login()
    {
        if (Auth::check()) { // Jika sudah login, redirect ke halaman home
            return redirect('/');
        }
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string|min:4|max:20',
            'password' => 'required|string|min:6|max:20'
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            // Regenerate session ID untuk keamanan
            $request->session()->regenerate();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'redirect' => url('/')
                ]);
            }

            return redirect()->intended('/');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => false,
                'message' => 'Username atau password salah'
            ]);
        }

        return redirect('login')->withErrors(['login' => 'Username atau password salah']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('login');
    }
}
