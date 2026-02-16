<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use Illuminate\Http\Request;

class GejalaController extends Controller
{
    public function index()
    {
        $data = Gejala::all();
        return view('gejala.index', compact('data'));
    }

    public function store(Request $request)
    {
        Gejala::create($request->all());
        return redirect()->route('gejala.index');
    }


    public function update(Request $request, Gejala $gejala)
    {
        $gejala->update($request->all());
        return redirect()->route('gejala.index');
    }

    public function destroy(Gejala $gejala)
    {
        $gejala->delete();
        return back();
    }
}
