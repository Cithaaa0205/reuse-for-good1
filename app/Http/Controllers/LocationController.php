<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function create()
    {
        return view('lokasi.setup');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'provinsi'  => ['required', 'string'],
            'kabupaten' => ['required', 'string'],
        ]);

        $user = Auth::user();
        $user->provinsi  = $data['provinsi'];
        $user->kabupaten = $data['kabupaten'];
        $user->save();

        return redirect()->route('home')->with('success', 'Lokasi berhasil disimpan ✅');
    }
}
