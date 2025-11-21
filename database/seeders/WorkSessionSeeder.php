<?php

namespace Database\Seeders;

use App\Models\WorkSession;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $now = Carbon::now('Europe/Paris');


        WorkSession::create([
            'WRK_UTI_ID' => 2,
            'WRK_Dte_Heure_Deb' => $now->copy()->subDays(1)->setTime(9, 0),
            'WRK_Dte_Heure_Fin' => $now->copy()->subDays(1)->setTime(17, 0),
            'WRK_Duree_Minutes' => 8 * 60,
            'WRK_Type_Cloture' => 'manuel',
            'WRK_Note' => 'Journée normale',
            'WRK_Cree_UID' => 1,
        ]);

        WorkSession::create([
            'WRK_UTI_ID' => 3,
            'WRK_Dte_Heure_Deb' => $now->copy()->subDays(1)->setTime(8, 30),
            'WRK_Dte_Heure_Fin' => $now->copy()->subDays(1)->setTime(16, 45),
            'WRK_Duree_Minutes' => 495,
            'WRK_Type_Cloture' => 'manuel',
            'WRK_Note' => 'Réunion le matin',
            'WRK_Cree_UID' => 1,
        ]);


        WorkSession::create([
            'WRK_UTI_ID' => 2,
            'WRK_Dte_Heure_Deb' => $now->copy()->setTime(8, 0),
            'WRK_Dte_Heure_Fin' => null,
            'WRK_Duree_Minutes' => null,
            'WRK_Type_Cloture' => null,
            'WRK_Note' => null,
            'WRK_Cree_UID' => 1,
        ]);
    }
}
