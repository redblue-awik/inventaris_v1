@extends('layouts.app')

@section('title', 'Data Mutasi Barang')
@section('header_title', 'Master Data Mutasi Barang')
@section('header_subtitle', 'Kelola mutasi stok dan pergerakan barang inventaris.')

@section('content')
    {{-- Tombol Tambah hanya untuk Admin & Gudang --}}
    @adminOrGudang
        <div class="flex justify-end mb-6">
            <button data-bs-toggle="modal" data-bs-target="#tambahModal"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-medium transition-all shadow-md flex items-center justify-center gap-2">
                <i class="fas fa-plus"></i> Tambah Mutasi
            </button>
        </div>
    @endadminOrGudang

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <table id="table-mutasiBarang" class="responsive nowrap whitespace-nowrap w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-100">
                    <th class="p-4 font-semibold">Tanggal & Jam</th>
                    <th class="p-4 font-semibold">Tipe</th>
                    <th class="p-4 font-semibold">Barang & Keterangan</th>
                    <th class="p-4 font-semibold">Mutasi</th>
                    <th class="p-4 font-semibold">Stok Akhir</th>
                    <th class="p-4 font-semibold">Petugas</th>
                    <th class="p-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mutasiBarang as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="p-4 text-slate-500 whitespace-nowrap">
                            {{ $item->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="p-4">
                            @php
                                $tipeConfig = [
                                    'masuk' => ['color' => 'text-emerald-600 bg-emerald-50', 'icon' => 'fa-arrow-down'],
                                    'keluar' => ['color' => 'text-red-600 bg-red-50', 'icon' => 'fa-arrow-up'],
                                    'opname' => [
                                        'color' => 'text-amber-600 bg-amber-50',
                                        'icon' => 'fa-clipboard-check',
                                    ],
                                    'transfer' => ['color' => 'text-blue-600 bg-blue-50', 'icon' => 'fa-exchange-alt'],
                                ];
                                $conf = $tipeConfig[$item->tipe];
                            @endphp
                            <span
                                class="px-3 py-1.5 rounded-lg text-xs font-bold inline-flex items-center gap-1.5 {{ $conf['color'] }}">
                                <i class="fas {{ $conf['icon'] }}"></i> {{ ucfirst($item->tipe) }}
                            </span>
                        </td>
                        <td class="p-4">
                            <p class="font-bold text-slate-800">{{ $item->barang->nama_barang ?? 'Barang Dihapus' }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $item->keterangan }}</p>
                        </td>
                        <td
                            class="p-4 text-center font-bold {{ $item->tipe === 'masuk' ? 'text-emerald-600' : ($item->tipe === 'keluar' ? 'text-red-500' : 'text-slate-700') }}">
                            {{ $item->tipe === 'masuk' ? '+' : ($item->tipe === 'keluar' ? '-' : '') }}{{ $item->jumlah }}
                        </td>
                        <td class="p-4 text-center font-semibold text-slate-600">
                            {{ $item->stok_sesudah }}
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-7 h-7 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center text-xs font-bold">
                                    {{ substr($item->staff->name ?? 'S', 0, 1) }}
                                </div>
                                <span class="text-sm font-medium">{{ $item->staff->name ?? 'Sistem' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-right">
                            {{-- Button Detail hanya untuk Admin & Gudang (optional: semua bisa lihat) --}}
                            @adminOrGudang
                                <button data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}"
                                    class="text-indigo-600 hover:text-indigo-800 mr-2" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}"
                                    class="text-indigo-600 hover:text-indigo-800 mr-2" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button data-bs-toggle="modal" data-bs-target="#modalHapus{{ $item->id }}"
                                    class="text-rose-600 hover:text-rose-800" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endadminOrGudang
                        </td>
                    </tr>

                    {{-- Modal hanya untuk Admin & Gudang --}}
                    @adminOrGudang
                        <!-- Modal Detail Mutasi -->
                        <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1"
                            aria-labelledby="detailModalLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-xl border-0 shadow-lg">
                                <div class="modal-header border-b border-slate-100 p-4">
                                    <h5 class="modal-title font-bold text-slate-800"
                                        id="detailModalLabel{{ $item->id }}">Detail Mutasi Barang</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="mb-4">
                                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Tanggal
                                            Mutasi</label>
                                        <p class="text-slate-800 font-medium">
                                            {{ $item->created_at->format('d M Y H:i:s') }}</p>
                                    </div>
                                    <div class="mb-4">
                                        <label
                                            class="block text-xs font-semibold text-slate-500 uppercase mb-1">Barang</label>
                                        <p class="text-slate-800 font-medium">
                                            {{ $item->barang->nama_barang ?? 'Barang Dihapus' }}</p>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Tipe
                                            Mutasi</label>
                                        @php
                                            $tipeConfig = [
                                                'masuk' => [
                                                    'color' => 'text-emerald-600 bg-emerald-50',
                                                    'icon' => 'fa-arrow-down',
                                                ],
                                                'keluar' => [
                                                    'color' => 'text-red-600 bg-red-50',
                                                    'icon' => 'fa-arrow-up',
                                                ],
                                                'opname' => [
                                                    'color' => 'text-amber-600 bg-amber-50',
                                                    'icon' => 'fa-clipboard-check',
                                                ],
                                                'transfer' => [
                                                    'color' => 'text-blue-600 bg-blue-50',
                                                    'icon' => 'fa-exchange-alt',
                                                ],
                                            ];
                                            $conf = $tipeConfig[$item->tipe];
                                        @endphp
                                        <span
                                            class="px-3 py-1.5 rounded-lg text-xs font-semibold inline-flex items-center gap-1.5 {{ $conf['color'] }}">
                                            <i class="fas {{ $conf['icon'] }}"></i> {{ ucfirst($item->tipe) }}
                                        </span>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Jumlah
                                            Mutasi</label>
                                        <p class="text-slate-800 font-medium">{{ $item->jumlah }}
                                            {{ $item->barang->satuan ?? 'Unit' }}</p>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Stok
                                            Sebelum</label>
                                        <p class="text-slate-800 font-medium">{{ $item->stok_sebelum }}
                                            {{ $item->barang->satuan ?? 'Unit' }}</p>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Stok
                                            Sesudah</label>
                                        <p class="text-slate-800 font-medium">{{ $item->stok_sesudah }}
                                            {{ $item->barang->satuan ?? 'Unit' }}</p>
                                    </div>
                                    <div class="mb-4">
                                        <label
                                            class="block text-xs font-semibold text-slate-500 uppercase mb-1">Keterangan</label>
                                        <p class="text-slate-800">{{ $item->keterangan ?? '-' }}</p>
                                    </div>
                                    <div class="mb-4">
                                        <label
                                            class="block text-xs font-semibold text-slate-500 uppercase mb-1">Petugas</label>
                                        <p class="text-slate-800 font-medium">{{ $item->staff->name ?? 'Sistem' }}</p>
                                    </div>
                                </div>
                                <div class="modal-footer border-t border-slate-100 p-4">
                                    <button type="button"
                                        class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg font-medium hover:bg-slate-300 transition"
                                        data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Edit Mutasi -->
                    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1"
                        aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-xl border-0 shadow-lg">
                                <form action="{{ route('mutasi_barang.update', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header border-b border-slate-100 p-4">
                                        <h5 class="modal-title font-bold text-slate-800"
                                            id="editModalLabel{{ $item->id }}">Edit Mutasi Barang</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Tipe
                                                Mutasi</label>
                                            <select name="tipe" class="form-control rounded-lg w-full" required>
                                                <option value="masuk" {{ $item->tipe === 'masuk' ? 'selected' : '' }}>
                                                    Masuk</option>
                                                <option value="keluar" {{ $item->tipe === 'keluar' ? 'selected' : '' }}>
                                                    Keluar</option>
                                                <option value="opname" {{ $item->tipe === 'opname' ? 'selected' : '' }}>
                                                    Opname</option>
                                                <option value="transfer"
                                                    {{ $item->tipe === 'transfer' ? 'selected' : '' }}>Transfer</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Jumlah</label>
                                            <input type="number" name="jumlah" value="{{ $item->jumlah }}"
                                                class="form-control rounded-lg w-full" min="1" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Keterangan</label>
                                            <textarea name="keterangan" class="form-control rounded-lg w-full" rows="3" required>{{ $item->keterangan }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-t border-slate-100 p-4">
                                        <button type="button"
                                            class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg font-medium hover:bg-slate-300 transition"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="submit"
                                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition">Simpan
                                            Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Hapus Mutasi -->
                    <div class="modal fade" id="modalHapus{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-xl border-0 shadow-lg">
                                <div class="modal-header bg-danger text-white border-0 p-4">
                                    <h5 class="modal-title font-bold">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white"
                                        data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <p>Apakah Anda yakin ingin menghapus mutasi untuk
                                        <strong>{{ $item->barang->nama_barang }}</strong>?
                                    </p>
                                    <p class="text-danger small mt-2"><i class="fas fa-warning"></i> Data yang dihapus
                                        tidak dapat dikembalikan dan stok akan direvert!</p>
                                </div>
                                <div class="modal-footer border-t border-slate-100 p-4">
                                    <button type="button"
                                        class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg font-medium hover:bg-slate-300 transition"
                                        data-bs-dismiss="modal">Batal</button>
                                    <form method="POST" id="formHapusMutasi{{ $item->id }}"
                                        action="{{ route('mutasi_barang.destroy', $item->id) }}"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-4 py-2 bg-danger text-white rounded-lg font-medium hover:bg-dark transition">Ya,
                                            Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endadminOrGudang
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fas fa-box-open text-4xl text-slate-300"></i>
                                <p>Belum ada data mutasi barang.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal Tambah hanya untuk Admin & Gudang --}}
    @adminOrGudang
        <!-- Modal Tambah Mutasi Barang -->
        <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-xl border-0 shadow-lg">
                <form action="{{ route('mutasi_barang.store') }}" method="POST">
                    @csrf
                    <div class="modal-header border-b border-slate-100 p-4">
                        <h5 class="modal-title font-bold text-slate-800" id="tambahModalLabel">Tambah Mutasi Barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Barang</label>
                            <select name="barang_id" class="form-control rounded-lg w-full" required>
                                <option value="" hidden disabled selected>Pilih Barang</option>
                                @foreach ($barangs as $barang)
                                    <option value="{{ $barang->id }}">
                                        {{ $barang->nama_barang }} (Stok: {{ $barang->stok_saat_ini }}
                                        {{ $barang->satuan }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tipe Mutasi</label>
                            <select name="tipe" class="form-control rounded-lg w-full" required>
                                <option value="" hidden disabled selected>Pilih Tipe</option>
                                <option value="masuk"> Masuk</option>
                                <option value="keluar"> Keluar</option>
                                <option value="opname"> Opname</option>
                                <option value="transfer"> Transfer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Jumlah</label>
                            <input type="number" name="jumlah" class="form-control rounded-lg w-full"
                                placeholder="Masukkan jumlah" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Keterangan</label>
                            <textarea name="keterangan" class="form-control rounded-lg w-full" rows="3"
                                placeholder="Jelaskan alasan mutasi..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-t border-slate-100 p-4">
                        <button type="button"
                            class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg font-medium hover:bg-slate-300 transition"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition">Simpan
                            Mutasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#table-mutasiBarang').DataTable({
                responsive: true,
                pagingType: "simple_numbers",
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari mutasi...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    paginate: {
                        previous: "‹",
                        next: "›"
                    },
                    zeroRecords: "Tidak ada data yang ditemukan",
                },
                columnDefs: [{
                        responsivePriority: 1,
                        targets: 0
                    },
                    {
                        responsivePriority: 5,
                        targets: 5
                    },
                    {
                        responsivePriority: 3,
                        targets: 6
                    }
                ]
            });
        });
    </script>
@endpush
