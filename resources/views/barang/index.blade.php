{{-- resources/views/barang/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Data Barang')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h2 class="text-xl font-bold">Daftar Barang</h2>
            <button onclick="openModal()" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-blue-700"><i class="fas fa-plus"></i> Tambah Barang</button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                    <tr><th class="px-5 py-3 text-left">Nama</th><th class="px-5 py-3 text-left">Kategori</th><th class="px-5 py-3 text-center">Stok</th><th class="px-5 py-3 text-center">Status</th><th class="px-5 py-3 text-center">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($barangs as $barang)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-5 py-3">{{ $barang->nama }}</td>
                        <td class="px-5 py-3"><span class="bg-gray-100 px-2 py-0.5 rounded-full text-xs">{{ $barang->kategori }}</span></td>
                        <td class="px-5 py-3 text-center font-semibold">{{ $barang->stok }}</td>
                        <td class="px-5 py-3 text-center">
                            @if($barang->stok <= 2) <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full text-xs">Kritis</span>
                            @elseif($barang->stok <=5) <span class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full text-xs">Menipis</span>
                            @else <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs">Tersedia</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-center">
                            <button onclick="editBarang({{ $barang->id }}, '{{ $barang->nama }}', '{{ $barang->kategori }}', {{ $barang->stok }})" class="text-blue-500 hover:text-blue-700 mx-1"><i class="fas fa-edit"></i></button>
                            <form method="POST" action="{{ route('barang.destroy', $barang) }}" style="display:inline;" onsubmit="return confirm('Yakin hapus?')">@csrf @method('DELETE')<button type="submit" class="text-red-400 hover:text-red-600"><i class="fas fa-trash-alt"></i></button></form>
                        </td>
                    </tr>
                    @empty <tr><td colspan="5" class="text-center py-6">Belum ada data</td></tr> @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
{{-- Modal Simple --}}
<div id="barangModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" onclick="closeModal(event)">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md" onclick="event.stopPropagation()">
        <h3 id="modalTitle" class="text-xl font-bold mb-4">Tambah Barang</h3>
        <form id="barangForm" method="POST" action="{{ route('barang.store') }}">
            @csrf <input type="hidden" name="_method" id="methodField" value="POST">
            <input type="hidden" name="id" id="barangId">
            <div class="mb-3"><label class="block text-sm font-medium">Nama</label><input type="text" name="nama" id="nama" class="w-full border rounded-xl px-3 py-2" required></div>
            <div class="mb-3"><label class="block text-sm font-medium">Kategori</label><input type="text" name="kategori" id="kategori" class="w-full border rounded-xl px-3 py-2" required></div>
            <div class="mb-3"><label class="block text-sm font-medium">Stok</label><input type="number" name="stok" id="stok" class="w-full border rounded-xl px-3 py-2" required min="0"></div>
            <div class="flex justify-end gap-2"><button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 rounded-xl">Batal</button><button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl">Simpan</button></div>
        </form>
    </div>
</div>
<script>
function openModal() { document.getElementById('modalTitle').innerText='Tambah Barang'; document.getElementById('methodField').value='POST'; document.getElementById('barangForm').action="{{ route('barang.store') }}"; document.getElementById('barangId').value=''; document.getElementById('nama').value=''; document.getElementById('kategori').value=''; document.getElementById('stok').value=''; document.getElementById('barangModal').classList.remove('hidden'); document.getElementById('barangModal').classList.add('flex'); }
function closeModal(e) { if(e && e.target !== e.currentTarget && e) return; document.getElementById('barangModal').classList.add('hidden'); document.getElementById('barangModal').classList.remove('flex'); }
function editBarang(id, nama, kategori, stok) { document.getElementById('modalTitle').innerText='Edit Barang'; document.getElementById('methodField').value='PUT'; document.getElementById('barangForm').action='/barang/'+id; document.getElementById('barangId').value=id; document.getElementById('nama').value=nama; document.getElementById('kategori').value=kategori; document.getElementById('stok').value=stok; openModal(); }
window.onclick = function(e) { if(e.target === document.getElementById('barangModal')) closeModal(); }
</script>
@endsection