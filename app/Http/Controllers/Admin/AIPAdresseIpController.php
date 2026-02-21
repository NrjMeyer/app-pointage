<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AIPAdresseIp;

class AIPAdresseIpController extends Controller
{
    public function index()
    {
        $ips = AIPAdresseIp::orderBy('created_at', 'desc')->get();
        return view('admin.ips.index', compact('ips'));
    }

    public function create()
    {
        return view('admin.ips.edit', ['ip' => new AIPAdresseIp()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'AIP_IP' => 'required|ip|unique:AIP_Adresse_IP,AIP_IP',
        ]);

        AIPAdresseIp::create([
            'AIP_IP' => $request->AIP_IP,
        ]);

        return redirect()->route('admin.ips.index')
            ->with('success', 'Adresse IP ajoutée avec succès.');
    }

    public function storeCurrent(Request $request)
    {
        $ip = $request->ip();

        if (AIPAdresseIp::where('AIP_IP', $ip)->exists()) {
            return redirect()->route('admin.ips.index')
                ->with('error', 'Votre adresse IP (' . $ip . ') est déjà dans la liste.');
        }

        AIPAdresseIp::create([
            'AIP_IP' => $ip,
        ]);

        return redirect()->route('admin.ips.index')
            ->with('success', 'Votre adresse IP (' . $ip . ') a été ajoutée.');
    }

    public function edit($id)
    {
        $ip = AIPAdresseIp::findOrFail($id);
        return view('admin.ips.edit', compact('ip'));
    }

    public function update(Request $request, $id)
    {
        $ip = AIPAdresseIp::findOrFail($id);

        $request->validate([
            'AIP_IP' => 'required|ip|unique:AIP_Adresse_IP,AIP_IP,' . $ip->AIP_ID . ',AIP_ID',
        ]);

        $ip->update([
            'AIP_IP' => $request->AIP_IP,
        ]);

        return redirect()->route('admin.ips.index')
            ->with('success', 'Adresse IP mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $ip = AIPAdresseIp::findOrFail($id);
        $ip->delete();

        return redirect()->route('admin.ips.index')
            ->with('success', 'Adresse IP supprimée avec succès.');
    }
}
