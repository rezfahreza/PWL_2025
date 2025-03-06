<?php

namespace App\Http\Controllers;

use App\Models\Stok;
use Illuminate\Http\Request;

class StokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stoks = Stok::all(); //Mengambil semua data stok dari database
        return view('stoks.index', compact('stoks'));  //Mengembalikan tampilan daftar stok
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('stoks.create');//Mengembalikan tampilan form tambah stok
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([//Validasi input form
            'nama' => 'required',
            'kondisi' => 'required',
        ]);

        //Hanya masukan atribut yang diizinkan
        Stok::create($request->only(['nama', 'kondisi']));
        return redirect()->route('stoks.index')->with('success', 'Stok added successfully');//Redirect ke daftar stok dengan pesan sukses
    }

    /**
     * Display the specified resource.
     */
    public function show(Stok $stoks)
    {
        return view('stoks.show', compact('stoks'));//Mengembalikan tampilan detail stok
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stok $stoks)
    {
        return view('stoks.edit', compact('stoks'));//Mengembalikan tampilan form edit stok
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stok $stoks)
    {
        $request->validate([//Validasi input form
            'nama' => 'required',
            'kondisi' => 'required',
        ]);

        //Hanya masukan atribut yang diizinkan
        Stok::create($request->only(['nama', 'kondisi']));
        return redirect()->route('stoks.index')->with('success', 'Stok updated successfully');//Redirect ke daftar stok dengan pesan sukses
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stok $stok)
    {
        $stok->delete();//Menghapus stok dari database
        return redirect()->route('stoks.index')->with('success', 'Stok deleted successfully');//Redirect ke daftar stok dengan pesan sukses
    }
}
