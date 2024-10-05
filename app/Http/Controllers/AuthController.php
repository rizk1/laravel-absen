<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Hash;
use Str;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {
            return redirect('/absen');
        }

        if ($request->action == 'login') return view('auth.login');
        if ($request->action == 'register') return view('auth.register');
    }
    
    public function loginUser(Request $request)
    {
        
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // if ($request->email != 'test@gmail.com') {
        //     return back()->withErrors([
        //         'error' => 'menten',
        //     ])->withInput();
        // }

        if (!$request->remember) {
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                
                return redirect()->intended('/absen')->with(['success-login' => 'Berhasil Login!']);
            }

            return back()->withErrors([
                'error' => 'Username atau password salah',
            ])->withInput();
        }else {
            if (Auth::attempt($credentials, $remember)) {
                $request->session()->regenerate();
    
                return redirect()->intended('/absen')->with(['success-login' => 'Berhasil Login!']);
            }

            return back()->withErrors([
                'error' => 'Username atau password salah',
            ])->withInput()->withInput();
        }
    }

    public function registerUser(Request $request)
    {
        $field = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6'
        ]);

        $rand = Str::random(60);
        $user = User::create([
            'name' => $field['name'],
            'email' => $field['email'],
            'password' => bcrypt($field['password']),
            'remember_token' => $rand
        ]);

        Auth::login($user);

        return redirect('/absen')->with(['success-login' => 'Berhasil Login!']);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/auth?action=login');
    }
}
