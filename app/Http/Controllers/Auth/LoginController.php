<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLogin()
    {
        $user = Auth::user();
        if($user){
            $redirect = $user->UTI_Role == "admin" ? 'admin/dashboard' : 'dashboard';
            return redirect($redirect);
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $credentials = [
            'UTI_Email' => $request->email,
            'password' => $request->password,
            'UTI_Actif' => 1,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $user->UTI_Login_Token = Str::random(60);
            $user->save();

            session(['uti_login_token' => $user->UTI_Login_Token]);
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Identifiants incorrects.',
        ])->withInput();
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Déconnexion réussie.');
    }
}
