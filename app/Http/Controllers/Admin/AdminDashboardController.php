<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use App\Models\WorkSession;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $months = [];
        $hoursData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            $minutes = WorkSession::whereBetween('WRK_Dte_Heure_Deb', [$start, $end])
                ->sum('WRK_Duree_Minutes');

            $months[] = ucfirst($date->locale('fr')->translatedFormat('M y'));
            $hoursData[] = round($minutes / 60, 1);
        }

        // Top 3 Jours ce mois
        $topDays = WorkSession::whereMonth('WRK_Dte_Heure_Deb', now()->month)
            ->whereYear('WRK_Dte_Heure_Deb', now()->year)
            ->selectRaw('DATE(WRK_Dte_Heure_Deb) as date, SUM(WRK_Duree_Minutes) as total_min')
            ->groupBy('date')
            ->orderByDesc('total_min')
            ->limit(3)
            ->get();

        return view('admin.dashboard', [
            'totalUsers'      => Utilisateur::where('UTI_Actif', 1)->where('UTI_Role', 'employe')->count(),
            'activeSessions'  => WorkSession::whereNull('WRK_Dte_Heure_Fin')->count(),
            'sessionsToday'   => WorkSession::whereDate('WRK_Dte_Heure_Deb', today())->count(),
            'months'          => $months,
            'hoursData'       => $hoursData,
            'topDays'         => $topDays,
            'totalHoursMonth' => round(WorkSession::whereMonth('WRK_Dte_Heure_Deb', now()->month)
                                    ->whereYear('WRK_Dte_Heure_Deb', now()->year)
                                    ->sum('WRK_Duree_Minutes') / 60, 1)
        ]);
    }
}
