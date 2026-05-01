<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with('barang')->orderBy('created_at', 'desc')->get();
        $barangs = Barang::where('stok', '>', 0)->get();
        return view('peminjaman.index', compact('peminjamans', 'barangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'peminjam' => 'required|string',
            'tgl_pinjam' => 'required|date',
            'jumlah' => 'required|integer|min:1'
        ]);

        $barang = Barang::findOrFail($validated['barang_id']);
        if ($barang->stok < $validated['jumlah']) {
            return back()->withErrors(['jumlah' => 'Stok tidak mencukupi']);
        }

        $barang->stok -= $validated['jumlah'];
        $barang->save();

        $validated['status'] = 'Dipinjam';
        Peminjaman::create($validated);

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil');
    }

    public function kembali(Peminjaman $peminjaman)
    {
        if ($peminjaman->status == 'Dikembalikan') {
            return back()->withErrors('Sudah dikembalikan');
        }

        $peminjaman->status = 'Dikembalikan';
        $peminjaman->tgl_kembali = now();
        $peminjaman->save();

        $barang = $peminjaman->barang;
        $barang->stok += $peminjaman->jumlah;
        $barang->save();

        return redirect()->route('peminjaman.index')->with('success', 'Barang dikembalikan');
    }
}