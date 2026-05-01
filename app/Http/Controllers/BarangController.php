<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::orderBy('nama')->get();
        return view('barang.index', compact('barangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'kategori' => 'required',
            'stok' => 'required|integer|min:0'
        ]);
        Barang::create($validated);
        return redirect()->route('barang.index')->with('success', 'Barang ditambahkan');
    }

    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'kategori' => 'required',
            'stok' => 'required|integer|min:0'
        ]);
        $barang->update($validated);
        return redirect()->route('barang.index')->with('success', 'Barang diupdate');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang dihapus');
    }
}