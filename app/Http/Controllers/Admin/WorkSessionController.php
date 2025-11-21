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
            'WRK_Duree_Min' => $request->WRK_Dte_Heure_Fin
                ? Carbon::parse($request->WRK_Dte_Heure_Fin)
                    ->diffInMinutes(Carbon::parse($request->WRK_Dte_Heure_Deb))
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
            $session->WRK_Duree_Minutes = Carbon::parse($session->WRK_Dte_Heure_Deb, 'Europe/Paris')
                ->diffInMinutes($session->WRK_Dte_Heure_Fin);
            $session->save();
        }

        return redirect()->route('admin.sessions.index')
            ->with('success', 'Session clôturée par l’administrateur.');
    }

    public function crossTable(Request $request)
    {

        $days = 7;

        $dates = collect();
        for ($i = $days - 1; $i >= 0; $i--) {
            $dates->push(now()->subDays($i)->format('Y-m-d'));
        }

        $users = Utilisateur::where('UTI_Role', 'employe')->orderBy('UTI_Nom')->get();


        $sessions = WorkSession::whereDate('WRK_Dte_Heure_Deb', '>=', now()->subDays($days - 1)->startOfDay())
            ->with('utilisateur')
            ->get();

        $grid = [];
        foreach ($users as $user) {
            foreach ($dates as $date) {
                $daySessions = $sessions->filter(function ($s) use ($user, $date) {
                    return $s->WRK_UTI_ID == $user->UTI_ID &&
                        \Carbon\Carbon::parse($s->WRK_Dte_Heure_Deb)->format('Y-m-d') == $date;
                });
                $grid[$user->UTI_ID][$date] = $daySessions;
            }
        }

        return view('admin.sessions.crosstable', compact('users', 'dates', 'grid'));
    }
}
