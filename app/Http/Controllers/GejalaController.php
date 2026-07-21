<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GejalaController extends Controller
{
    public function index()
    {
        $data = Gejala::paginate(10);

        $totalbobot = Gejala::whereNotNull('bobot_cbr')->count();
        return view('gejala.index', compact('data', 'totalbobot'));
    }

    public function store(Request $request)
    {
        Gejala::create($request->all());
        return redirect()->route('gejala.index')->with('success','Berhasil Menambahkan Data');
    }


    public function update(Request $request, Gejala $gejala)
    {
        $gejala->update($request->all());
        return redirect()->route('gejala.index')->with('success','Berhasil Update Data Gejala');
    }

    public function destroy(Gejala $gejala)
    {
        $gejala->delete();
        return back()->with('success', 'Berhasil Menghapus Data');
    }
}
