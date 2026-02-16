<?php

namespace App\Http\Controllers;

use App\Models\PenyakitHama;
use Illuminate\Http\Request;

class PenyakitHamaController extends Controller
{
    public function index()
    {
        $data = PenyakitHama::paginate(10);
        return view('penyakit.index', compact('data'));
    }

    public function store(Request $request)
    {
        PenyakitHama::create($request->all());
        return redirect()->route('penyakit.index');
    }

    public function update(Request $request, PenyakitHama $penyakit)
    {
        $penyakit->update($request->all());
        return redirect()->route('penyakit.index');
    }

    public function destroy(PenyakitHama $penyakit)
    {
        $penyakit->delete();
        return back();
    }
}
