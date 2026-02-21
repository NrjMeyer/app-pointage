<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\WorkSession;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord utilisateur.
     */
    public function index()
    {
        $user = Auth::user();

        $sessionActive = WorkSession::where('WRK_UTI_ID', $user->UTI_ID)
            ->whereNull('WRK_Dte_Heure_Fin')
            ->first();

        $sevenDaysAgo = Carbon::now('Europe/Paris')->subDays(7);

        $sessions7Jours = WorkSession::where('WRK_UTI_ID', $user->UTI_ID)
            ->where('WRK_Dte_Heure_Deb', '>=', $sevenDaysAgo)
            ->orderBy('WRK_Dte_Heure_Deb', 'desc')
            ->get();

        return view('dashboard', [
            'sessionActive'   => $sessionActive,
            'sessions7Jours'  => $sessions7Jours,
        ]);
    }

    public function pointage()
    {
        $user = Auth::user();

        $sessionActive = WorkSession::where('WRK_UTI_ID', $user->UTI_ID)
            ->whereNull('WRK_Dte_Heure_Fin')
            ->first();


        return view('pointage', [
            'sessionActive'   => $sessionActive,
        ]);
    }

    /**
     * Démarrer une nouvelle session.
     */
    public function startSession(Request $request)
    {
        $user = Auth::user();

        // Vérification IP pour les non-admins
        if ($user->UTI_Role !== 'admin' && $user->UTI_IP_Restriction) {
            $currentIp = $request->ip();
            $isAllowed = \App\Models\AIPAdresseIp::where('AIP_IP', $currentIp)->exists();

            if (!$isAllowed) {
                return back()->with('error', 'Action impossible en dehors des bureaux.');
            }
        }

        $active = WorkSession::where('WRK_UTI_ID', $user->UTI_ID)
            ->whereNull('WRK_Dte_Heure_Fin')
            ->first();

        if ($active) {
            return back()->with('error', 'Une session est déjà active.');
        }

        $now = Carbon::now('Europe/Paris')->format('H:i');
        $open   = env('POINTEUSE_HEURE_OUVERTURE', '05:00');
        $close  = env('POINTEUSE_HEURE_CLOTURE', '22:00');

        $horsHoraires = !($now >= $open && $now < $close);

        if ($horsHoraires) {
            return back()->with('error', 'Vous ne pouvez pas démarrer une session en dehors des horaires autorisés.');
        }

        WorkSession::create([
            'WRK_UTI_ID'        => $user->UTI_ID,
            'WRK_Dte_Heure_Deb' => Carbon::now('Europe/Paris'),
            'WRK_Est_Cloture_Auto' => false,
            'WRK_IP_Debut'     => $request->ip(),
        ]);

        return back()->with('success', 'Session démarrée.');
    }

    /**
     * Clôturer une session active.
     */
    public function closeSession(Request $request)
    {
        $user = Auth::user();

        // Vérification IP pour les non-admins
        if ($user->UTI_Role !== 'admin' && $user->UTI_IP_Restriction) {
            $currentIp = $request->ip();
            $isAllowed = \App\Models\AIPAdresseIp::where('AIP_IP', $currentIp)->exists();

            if (!$isAllowed) {
                return back()->with('error', 'Action impossible en dehors des bureaux.');
            }
        }

        $session = WorkSession::where('WRK_UTI_ID', $user->UTI_ID)
            ->whereNull('WRK_Dte_Heure_Fin')
            ->first();

        if (!$session) {
            return back()->with('error', 'Aucune session active à clôturer.');
        }

        $now = Carbon::now('Europe/Paris');

        $debut = Carbon::parse($session->WRK_Dte_Heure_Deb, 'Europe/Paris');
        $limit = $debut->copy()->setTime(22, 0, 0);

        if ($now->greaterThan($limit)) {
            $now = $limit;
        }
        $duree = $debut->diffInMinutes($now);

        $session->update([
            'WRK_Dte_Heure_Fin'  => $now,
            'WRK_Duree_Minutes'      => $duree,
            'WRK_Type_Cloture'   => 'manuel',
            'WRK_Est_Cloture_Auto' => false,
            'WRK_Note'           => $request->WRK_Note,
            'WRK_IP_Fin'        => $request->ip(),
        ]);

        return back()->with('success', 'Session clôturée.');
    }
}
