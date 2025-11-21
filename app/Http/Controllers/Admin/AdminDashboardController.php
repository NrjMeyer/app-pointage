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
        return view('admin.dashboard', [
            'totalUsers'      => Utilisateur::count(),
            'activeSessions'  => WorkSession::whereNull('WRK_Dte_Heure_Fin')->count(),
            'sessionsToday'   => WorkSession::whereDate('WRK_Dte_Heure_Deb', today())->count(),
        ]);
    }
}
