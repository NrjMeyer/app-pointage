<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = Utilisateur::orderBy('UTI_Nom')->get();
        return view('admin.users.index', compact('users'));
    }


    public function create()
    {
        return view('admin.users.edit', ['user' => new Utilisateur()]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'UTI_Nom' => 'required|string|max:192',
            'UTI_Email' => 'required|email|unique:UTI_Utilisateur,UTI_Email',
            'UTI_Password' => 'required|string|min:6',
            'UTI_Role' => 'required|in:employe,admin',
            'UTI_Actif' => 'required|boolean',
            'UTI_IP_Restriction' => 'required|boolean',
        ]);

        Utilisateur::create([
            'UTI_Nom' => $request->UTI_Nom,
            'UTI_Email' => $request->UTI_Email,
            'UTI_Password' => Hash::make($request->UTI_Password),
            'UTI_Role' => $request->UTI_Role,
            'UTI_Actif' => $request->UTI_Actif,
            'UTI_IP_Restriction' => $request->UTI_IP_Restriction,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    // Formulaire édition
    public function edit($id)
    {
        $user = Utilisateur::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // Mise à jour
    public function update(Request $request, $id)
    {
        $user = Utilisateur::findOrFail($id);

        $request->validate([
            'UTI_Nom' => 'required|string|max:192',
            'UTI_Email' => 'required|email|unique:UTI_Utilisateur,UTI_Email,' . $user->UTI_ID . ',UTI_ID',
            'UTI_Password' => 'nullable|string|min:6',
            'UTI_Role' => 'required|in:employe,admin',
            'UTI_Actif' => 'required|boolean',
            'UTI_IP_Restriction' => 'required|boolean',
        ]);

        $user->UTI_Nom = $request->UTI_Nom;
        $user->UTI_Email = $request->UTI_Email;
        $user->UTI_Role = $request->UTI_Role;
        $user->UTI_Actif = $request->UTI_Actif;
        $user->UTI_IP_Restriction = $request->UTI_IP_Restriction;

        if ($request->UTI_Password) {
            $user->UTI_Password = Hash::make($request->UTI_Password);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }


    public function destroy($id)
    {
        if (auth()->id() == $id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user = Utilisateur::findOrFail($id);

        if ($user->sessions()->exists()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Impossible de supprimer cet utilisateur car il possède des sessions de travail (historique de pointage).');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
}
