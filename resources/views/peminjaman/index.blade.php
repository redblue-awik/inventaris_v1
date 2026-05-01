{{-- resources/views/peminjaman/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Peminjaman')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Form Peminjaman --}}
        <div class="bg-white rounded-2xl shadow-md p-5">
            <h3 class="font-bold text-lg mb-4"><i class="fas fa-hand-holding text-amber-500"></i> Form Peminjaman</h3>
            <form method="POST" action="{{ route('peminjaman.store') }}">
                @csrf
                <div class="mb-3"><label class="block text-sm font-medium">Barang</label><select name="barang_id" class="w-full border rounded-xl px-3 py-2" required> <option value="">Pilih Barang</option> @foreach($barangs as $barang) <option value="{{ $barang->id }}">{{ $barang->nama }} (Stok: {{ $barang->stok }})</option> @endforeach </select></div>
                <div class="mb-3"><label class="block text-sm font-medium">Peminjam</label><input type="text" name="peminjam" class="w-full border rounded-xl px-3 py-2" required></div>
                <div class="mb-3"><label class="block text-sm font-medium">Tanggal Pinjam</label><input type="date" name="tgl_pinjam" class="w-full border rounded-xl px-3 py-2" value="{{ date('Y-m-d') }}" required></div>
                <div class="mb-3"><label class="block text-sm font-medium">Jumlah</label><input type="number" name="jumlah" min="1" value="1" class="w-full border rounded-xl px-3 py-2" required></div>
                <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white py-2 rounded-xl font-semibold"><i class="fas fa-save"></i> Pinjam</button>
            </form>
        </div>
        {{-- Daftar Peminjaman Aktif --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="px-5 py-3 bg-gray-50 border-b"><h3 class="font-bold">Peminjaman Aktif</h3></div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100"><tr><th class="px-4 py-2 text-left">Barang</th><th class="px-4 py-2 text-left">Peminjam</th><th class="px-4 py-2 text-left">Tgl Pinjam</th><th class="px-4 py-2 text-center">Jml</th><th class="px-4 py-2 text-center">Aksi</th></tr></thead>
                    <tbody>
                        @foreach($peminjamans->where('status','Dipinjam') as $p)
                        <tr class="border-b"><td class="px-4 py-2">{{ $p->barang->nama }}</td><td>{{ $p->peminjam }}</td><td>{{ $p->tgl_pinjam->format('d/m/Y') }}</td><td class="text-center">{{ $p->jumlah }}</td><td class="text-center"><form method="POST" action="{{ route('peminjaman.kembali', $p) }}">@csrf <button class="bg-green-100 text-green-700 px-2 py-1 rounded-lg text-xs hover:bg-green-200"><i class="fas fa-undo-alt"></i> Kembalikan</button></form></td></tr>
                        @endforeach
                        @if($peminjamans->where('status','Dipinjam')->isEmpty()) <tr><td colspan="5" class="text-center py-4">Tidak ada peminjaman aktif</td></tr> @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- Riwayat --}}
    <div class="mt-8 bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="px-5 py-3 bg-gray-50 border-b"><h3 class="font-semibold">Riwayat Peminjaman</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100"><tr><th class="px-4 py-2">Barang</th><th>Peminjam</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th></tr></thead>
                <tbody>
                    @foreach($peminjamans->where('status','Dikembalikan') as $p)
                    <tr><td class="px-4 py-2">{{ $p->barang->nama }}</td><td>{{ $p->peminjam }}</td><td>{{ $p->tgl_pinjam->format('d/m/Y') }}</td><td>{{ optional($p->tgl_kembali)->format('d/m/Y') }}</td><td><span class="bg-gray-200 px-2 py-0.5 rounded-full text-xs">Dikembalikan</span></td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection