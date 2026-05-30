<?php

namespace App\Http\Controllers;

use App\Models\permintaan;
use App\Models\Barang; // Pastikan model Barang di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermintaanController extends Controller
{
    public function index()
    {
        $permintaans = permintaan::with(['permohonan', 'barang', 'disetujui'])->latest()->get();
        $barangs = Barang::where('stok_saat_ini', '>', 0)->get(); 
        return view('permintaans', compact('permintaans', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id'      => 'required|exists:barangs,id',
            'jumlah_diminta' => 'required|integer|min:1',
            'keperluan'      => 'required|string|max:255',
        ]);

        permintaan::create([
            'permohonan_id'  => Auth::id(),
            'barang_id'      => $request->barang_id,
            'jumlah_diminta' => $request->jumlah_diminta,
            'keperluan'      => $request->keperluan,
            'status'         => 'menunggu',
        ]);
        
        return redirect()->back()->with('success', 'Permintaan barang berhasil diajukan.');
    }

    public function update(Request $request, string $id)
    {
        $permintaan = permintaan::findOrFail($id);

        $request->validate([
            'barang_id'      => 'required|exists:barangs,id',
            'jumlah_diminta' => 'required|integer|min:1',
            'keperluan'      => 'required|string|max:255',
            'status'         => 'required|in:menunggu,disetujui,ditolak,diserahkan',
        ]);

        // Tambahkan validasi jumlah_disetujui hanya jika status disetujui
        if ($request->status === 'disetujui') {
            $request->validate([
                'jumlah_disetujui' => 'required|integer|min:1|max:' . $request->jumlah_diminta,
            ]);
        }

        // Jika disetujui, catat siapa yang menyetujui
        $disetujui_oleh = in_array($request->status, ['disetujui', 'diserahkan']) ? Auth::id() : null;

        $updateData = [
            'barang_id'      => $request->barang_id,
            'jumlah_diminta' => $request->jumlah_diminta,
            'keperluan'      => $request->keperluan,
            'status'         => $request->status,
            'disetujui_oleh' => $disetujui_oleh,
        ];

        // Hanya tambahkan jumlah_disetujui jika status disetujui
        if ($request->status === 'disetujui') {
            $updateData['jumlah_disetujui'] = $request->jumlah_disetujui;
        }

        $permintaan->update($updateData);

        $statusMsg = $request->status === 'disetujui' ? 'disetujui' : 'ditolak';
        return redirect()->back()->with('success', "Permintaan berhasil $statusMsg.");
    }

        public function destroy(string $id)
        {
            $permintaan = permintaan::findOrFail($id);
            $permintaan->delete();
    
            return redirect()->back()->with('success', 'Permintaan berhasil dihapus.');
        }
}