<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Hash;
use Str;

class UserController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect('/absen');
        }
        return view('auth.auth-page');
    }
    
    public function loginUser(Request $request)
    {
        
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($request->email != 'test@gmail.com') {
            return back()->withErrors([
                'error' => 'menten',
            ])->withInput();
        }

        if (!$request->remember) {
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
    
                return redirect()->intended('/absen');
            }

            return back()->withErrors([
                'error' => 'Username atau password salah',
            ])->withInput();
        }else {
            if (Auth::attempt($credentials, $remember)) {
                $request->session()->regenerate();
    
                return redirect()->intended('dashboard');
            }

            return back()->withErrors([
                'error' => 'Username atau password salah',
            ])->withInput()->withInput();
        }
    }

    public function registerUser(Request $request)
    {
        return back()->withErrors([
            'error' => 'menten',
        ])->withInput();

        $field = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $rand = Str::random(60);
        $user = User::create([
            'name' => $field['name'],
            'email' => $field['email'],
            'password' => bcrypt($field['password']),
            'remember_token' => $rand
        ]);

        Auth::login($user);

        return redirect('/absen');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/auth?action=login');
    }
}
