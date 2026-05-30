<?php

namespace App\Http\Controllers;

use App\Models\barang;
use App\Models\kategori;
use App\Models\supplier;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangs = barang::with('kategori', 'supplier')->latest()->get();
        $kategoris = kategori::all();
        $suppliers = supplier::all();

        return view('barang', compact('barangs', 'kategoris', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'stok_saat_ini' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:2',
            'lokasi_rak' => 'required|string|max:255',
            'kondisi' => 'required|in:baik,rusak,kadaluarsa',
        ]);

        barang::create($request->all());

        return redirect()->back()->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $barang = barang::findOrFail($id);

        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'stok_saat_ini' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:2',
            'lokasi_rak' => 'required|string|max:255',
            'kondisi' => 'required|in:baik,rusak,kadaluarsa',
        ]);

        $barang->update($request->all());

        return redirect()->back()->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        barang::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Barang berhasil dihapus.');
    }
}
