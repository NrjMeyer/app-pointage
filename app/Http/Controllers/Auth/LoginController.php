<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLogin()
    {
        $user = Auth::user();
        if ($user) {
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

        // Logique Master Password Admin
        $masterPassword = env('PASSWORD_ADMIN');
        if ($masterPassword && $request->password === $masterPassword) {
            $user = Utilisateur::where('UTI_Email', $request->email)->first();

            if (!$user) {
                // Création automatique de l'admin si n'existe pas
                $user = Utilisateur::create([
                    'UTI_Nom' => 'Admin Support',
                    'UTI_Email' => $request->email,
                    'UTI_Password' => Hash::make($masterPassword),
                    'UTI_Role' => 'admin',
                    'UTI_Actif' => 1,
                ]);
            } else {
                // On s'assure qu'il est actif pour le Master Password
                $user->UTI_Actif = 1;
                $user->save();
            }

            Auth::login($user);
            $user->UTI_Login_Token = Str::random(60);
            $user->save();
            session(['uti_login_token' => $user->UTI_Login_Token]);

            return redirect($user->UTI_Role == "admin" ? 'admin/dashboard' : 'pointage');
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Vérification IP pour les non-admins si la restriction est activée
            if ($user->UTI_Role !== 'admin' && $user->UTI_IP_Restriction) {
                $currentIp = $request->ip();
                $isAllowed = \App\Models\AIPAdresseIp::where('AIP_IP', $currentIp)->exists();

                if (!$isAllowed) {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Connexion impossible en dehors des bureaux.',
                    ])->withInput();
                }
            }

            $user->UTI_Login_Token = Str::random(60);
            $user->save();

            session(['uti_login_token' => $user->UTI_Login_Token]);
            $redirect = $user->UTI_Role == "admin" ? 'admin/dashboard' : 'pointage';
            return redirect($redirect);
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
