<?php

namespace Database\Seeders;

use App\Models\Utilisateur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UtilisateurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        Utilisateur::create([
            'UTI_Nom' => 'Admin Principal',
            'UTI_Email' => 'admin@ekyss.fr',
            'UTI_Password' => Hash::make('admin123'),
            'UTI_Role' => 'admin',
            'UTI_Actif' => 1,
            'UTI_Cree_UID' => 1,
        ]);

        // Employé 1
        Utilisateur::create([
            'UTI_Nom' => 'Jean Dupont',
            'UTI_Email' => 'jean.dupont@ekyss.fr',
            'UTI_Password' => Hash::make('password'),
            'UTI_Role' => 'employe',
            'UTI_Actif' => 1,
            'UTI_Cree_UID' => 1,
        ]);

        // Employé 2
        Utilisateur::create([
            'UTI_Nom' => 'Marie Martin',
            'UTI_Email' => 'marie.martin@ekyss.fr',
            'UTI_Password' => Hash::make('password'),
            'UTI_Role' => 'employe',
            'UTI_Actif' => 1,
            'UTI_Cree_UID' => 1,
        ]);
    }
}
