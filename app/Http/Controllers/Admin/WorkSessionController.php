<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use App\Models\WorkSession;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorkSessionController extends Controller
{
    public function index(Request $request)
    {
        $ws = WorkSession::with('utilisateur')
            ->orderBy('WRK_Dte_Heure_Deb', 'desc');

        if ($request->has('user')) {
            $ws->where('WRK_UTI_ID', $request->user);
        }
        if ($request->has('date')) {
            $ws->whereDate('WRK_Dte_Heure_Deb', $request->date);
        }
        $sessions = $ws->get();

        return view('admin.sessions.index', compact('sessions'));
    }

    public function edit($id)
    {
        $session = WorkSession::findOrFail($id);
        $users = Utilisateur::all();

        return view('admin.sessions.edit', compact('session', 'users'));
    }

    public function update(Request $request, $id)
    {
        $session = WorkSession::findOrFail($id);

        $request->validate([
            'WRK_UTI_ID' => 'required|exists:UTI_Utilisateur,UTI_ID',
            'WRK_Dte_Heure_Deb' => 'required|date',
            'WRK_Dte_Heure_Fin' => 'nullable|date|after_or_equal:WRK_Dte_Heure_Deb',
            'WRK_Note' => 'nullable|string|max:255',
        ]);

        $session->update([
            'WRK_UTI_ID' => $request->WRK_UTI_ID,
            'WRK_Dte_Heure_Deb' => $request->WRK_Dte_Heure_Deb,
            'WRK_Dte_Heure_Fin' => $request->WRK_Dte_Heure_Fin,
            'WRK_Duree_Minutes' => $request->WRK_Dte_Heure_Fin
                ? Carbon::parse($request->WRK_Dte_Heure_Deb)
                ->diffInMinutes(Carbon::parse($request->WRK_Dte_Heure_Fin))
                : null,
            'WRK_Type_Cloture' => $request->WRK_Dte_Heure_Fin ? 'manuel' : null,
            'WRK_Note' => $request->WRK_Note,
        ]);

        return redirect()->route('admin.sessions.index')
            ->with('success', 'Session mise à jour avec succès.');
    }


    public function create()
    {
        $users = Utilisateur::all();
        return view('admin.sessions.edit', ['session' => new WorkSession(), 'users' => $users]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'WRK_UTI_ID' => 'required|exists:UTI_Utilisateur,UTI_ID',
            'WRK_Dte_Heure_Deb' => 'required|date',
            'WRK_Dte_Heure_Fin' => 'nullable|date|after_or_equal:WRK_Dte_Heure_Deb',
            'WRK_Note' => 'nullable|string|max:255',
        ]);

        $fin = $request->WRK_Dte_Heure_Fin;
        $duree = $fin ? Carbon::parse($fin)->diffInMinutes(Carbon::parse($request->WRK_Dte_Heure_Deb)) : null;

        WorkSession::create([
            'WRK_UTI_ID' => $request->WRK_UTI_ID,
            'WRK_Dte_Heure_Deb' => $request->WRK_Dte_Heure_Deb,
            'WRK_Dte_Heure_Fin' => $fin,
            'WRK_Duree_Minutes' => $duree,
            'WRK_Type_Cloture' => $fin ? 'manuel' : null,
            'WRK_Note' => $request->WRK_Note,
        ]);

        return redirect()->route('admin.sessions.index')
            ->with('success', 'Session créée avec succès.');
    }

    public function destroy($id)
    {
        $session = WorkSession::findOrFail($id);
        $session->delete();

        return redirect()->route('admin.sessions.index')
            ->with('success', 'Session supprimée avec succès.');
    }

    public function forceClose($id)
    {
        $session = WorkSession::findOrFail($id);
        if (!$session->WRK_Dte_Heure_Fin) {
            $session->WRK_Dte_Heure_Fin = Carbon::now('Europe/Paris');
            $session->WRK_Type_Cloture = 'admin';
            $session->WRK_Duree_Minutes = Carbon::parse($session->WRK_Dte_Heure_Deb, 'Europe/Paris')
                ->diffInMinutes($session->WRK_Dte_Heure_Fin);
            $session->save();
        }

        return redirect()->route('admin.sessions.index')
            ->with('success', 'Session clôturée par l’administrateur.');
    }

    public function crossTable(Request $request)
    {

        $selectedMonth = $request->input('month', now()->format('Y-m'));
        $start = Carbon::parse($selectedMonth . '-01')->startOfMonth();
        $end   = min(Carbon::parse($selectedMonth . '-01')->endOfMonth(), Carbon::now()->endOfDay());

        $utilisateurs = Utilisateur::where('UTI_Actif', 1)->where('UTI_Role', 'employe')->orderBy('UTI_Nom')->get();

        // Fetch all sessions for the period
        $sessions = WorkSession::whereBetween('WRK_Dte_Heure_Deb', [$start, $end])
            ->orderBy('WRK_Dte_Heure_Deb')
            ->get();

        $totauxMois = [];

        foreach ($utilisateurs as $user) {
            // Calculate total for user, handling ongoing sessions
            $userSessions = $sessions->where('WRK_UTI_ID', $user->UTI_ID);
            $total = 0;
            foreach ($userSessions as $session) {
                if ($session->WRK_Duree_Minutes) {
                    $total += $session->WRK_Duree_Minutes;
                } elseif (!$session->WRK_Dte_Heure_Fin) {
                    // Ongoing session: calculate duration until now
                    $total += Carbon::parse($session->WRK_Dte_Heure_Deb)->diffInMinutes(Carbon::now());
                }
            }
            $totauxMois[$user->UTI_ID] = $total;
        }

        $jours = collect();
        for ($d = $end->copy(); $d >= $start; $d->subDay()) {
            $jours->push($d->format('Y-m-d'));
        }

        $pivot = [];

        foreach ($jours as $j) {
            $pivot[$j] = [];

            foreach ($utilisateurs as $u) {
                $dailySessions = $sessions
                    ->where('WRK_UTI_ID', $u->UTI_ID)
                    ->filter(function ($s) use ($j) {
                        return Carbon::parse($s->WRK_Dte_Heure_Deb)->format('Y-m-d') === $j;
                    });

                $total = 0;
                foreach ($dailySessions as $session) {
                    if ($session->WRK_Duree_Minutes) {
                        $total += $session->WRK_Duree_Minutes;
                    } elseif (!$session->WRK_Dte_Heure_Fin) {
                        // Ongoing session
                        $total += Carbon::parse($session->WRK_Dte_Heure_Deb)->diffInMinutes(Carbon::now());
                    }
                }

                $pivot[$j][$u->UTI_ID] = $total;
            }
        }

        return view('admin.sessions.crosstable', compact('utilisateurs', 'pivot', 'jours', 'selectedMonth', 'totauxMois'));
    }
}
