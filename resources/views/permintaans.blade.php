@extends('layouts.app')

@section('title', 'Data Permintaan')
@section('header_title', 'Master Data Permintaan')
@section('header_subtitle', 'Kelola informasi permintaan barang inventaris.')

@section('content')
    {{-- Tombol Tambah hanya untuk Staff (dan admin/gudang bisa juga) --}}
    <div class="flex justify-end mb-6">
        <button data-bs-toggle="modal" data-bs-target="#tambahModal"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-medium transition-all shadow-md flex items-center justify-center gap-2">
            <i class="fas fa-plus"></i> Tambah Permintaan
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <table id="table-permintaan" class="responsive nowrap whitespace-nowrap w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-100">
                    <th class="p-4 font-semibold rounded-tl-xl">No. Permintaan</th>
                    <th class="p-4 font-semibold">Pemohon</th>
                    <th class="p-4 font-semibold">Barang & Jumlah</th>
                    <th class="p-4 font-semibold">Keperluan</th>
                    <th class="p-4 font-semibold">Status</th>
                    <th class="p-4 font-semibold rounded-tr-xl text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permintaans as $item)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-700 cursor-pointer">{{ $item->no_permintaan }}</td>
                        <td class="px-4 py-3 font-bold text-slate-800">{{ $item->permohonan->name ?? 'Unknown' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->barang->nama_barang ?? 'Unknown' }} ({{ $item->jumlah_diminta }})</td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->keperluan ?? '-' }}</td>
                        <td class="px-4 py-3 text-slate-600">
                            @php
                                $statusColors = [
                                    'menunggu' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'disetujui' => 'bg-green-100 text-green-800 border-green-200',
                                    'ditolak' => 'bg-red-100 text-red-800 border-red-200',
                                    'diserahkan' => 'bg-blue-100 text-blue-800 border-blue-200'
                                ];
                                $color = $statusColors[$item->status] ?? 'bg-slate-50 text-slate-600 border-slate-200';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $color }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            {{-- Tombol Detail - Semua bisa lihat --}}
                            <button data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}"
                                class="text-indigo-600 hover:text-indigo-800 mr-2" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </button>

                            {{-- Tombol Edit - Hanya untuk permintaan menunggu, staff hanya miliknya sendiri, gudang/admin untuk semua --}}
                            @if($item->status === 'menunggu')
                                @if(Auth::user()->role === 'staff' && Auth::user()->id === $item->permohonan_id || Auth::user()->role !== 'staff')
                                    <button data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}"
                                        class="text-blue-600 hover:text-blue-800 mr-2" title="Edit Permintaan">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                @endif
                            @endif

                            {{-- Tombol Approve/Reject - Hanya untuk Gudang & Admin --}}
                            @adminOrGudang
                                <button type="button" onclick="checkPermintaanStatus(event, '{{ $item->status }}')"
                                    data-bs-toggle="modal" data-bs-target="#terimaTolaKModal{{ $item->id }}"
                                    class="text-emerald-600 hover:text-emerald-800 mr-2 {{ $item->status !== 'menunggu' ? 'opacity-50 cursor-not-allowed' : '' }}" 
                                    title="{{ $item->status !== 'menunggu' ? 'Permintaan sudah ' . ucfirst($item->status) : 'Terima/Tolak' }}"
                                    {{ $item->status !== 'menunggu' ? 'disabled' : '' }}>
                                    <i class="fas fa-check-circle"></i>
                                </button>
                            @endadminOrGudang

                            {{-- Tombol Delete - Hanya untuk menunggu, staff hanya miliknya sendiri, gudang/admin untuk semua --}}
                            @if($item->status === 'menunggu')
                                @if(Auth::user()->role === 'staff' && Auth::user()->id === $item->permohonan_id || Auth::user()->role !== 'staff')
                                    <button data-bs-toggle="modal" data-bs-target="#modalHapus{{ $item->id }}"
                                        class="text-rose-600 hover:text-rose-800" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            @endif
                        </td>
                    </tr>

                    <!-- Modal Detail Permintaan -->
                    <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1"
                        aria-labelledby="detailModalLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-xl border-0 shadow-lg">
                                <div class="modal-header border-b border-slate-100 p-4">
                                    <h5 class="modal-title font-bold text-slate-800"
                                        id="detailModalLabel{{ $item->id }}">Detail Permintaan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="mb-4">
                                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">No. Permintaan</label>
                                        <p class="text-slate-800 font-medium">{{ $item->no_permintaan }}</p>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Pemohon</label>
                                        <p class="text-slate-800 font-medium">{{ $item->permohonan->name ?? 'Unknown' }}</p>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Barang</label>
                                        <p class="text-slate-800 font-medium">{{ $item->barang->nama_barang ?? 'Unknown' }}</p>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Jumlah Diminta</label>
                                        <p class="text-slate-800 font-medium">{{ $item->jumlah_diminta }} {{ $item->barang->satuan ?? '' }}</p>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Jumlah Disetujui</label>
                                        <p class="text-slate-800 font-medium">{{ $item->jumlah_disetujui ?? '-' }} {{ $item->barang->satuan ?? '' }}</p>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Keperluan</label>
                                        <p class="text-slate-800">{{ $item->keperluan ?? '-' }}</p>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Status</label>
                                        @php
                                            $statusColors = [
                                                'menunggu' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                'disetujui' => 'bg-green-100 text-green-800 border-green-200',
                                                'ditolak' => 'bg-red-100 text-red-800 border-red-200',
                                                'diserahkan' => 'bg-blue-100 text-blue-800 border-blue-200'
                                            ];
                                            $color = $statusColors[$item->status] ?? 'bg-slate-50 text-slate-600 border-slate-200';
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $color }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Disetujui Oleh</label>
                                        <p class="text-slate-800">{{ $item->disetujui?->name ?? '-' }}</p>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Tanggal Permintaan</label>
                                        <p class="text-slate-800">{{ $item->created_at->format('d M Y H:i') }}</p>
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

                    {{-- Modal Edit Permintaan - Hanya untuk menunggu, staff hanya miliknya, gudang/admin untuk semua --}}
                    @if($item->status === 'menunggu' && (Auth::user()->role === 'staff' && Auth::user()->id === $item->permohonan_id || Auth::user()->role !== 'staff'))
                        <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1"
                            aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-xl border-0 shadow-lg">
                                    <form action="{{ route('permintaan.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header border-b border-slate-100 p-4">
                                            <h5 class="modal-title font-bold text-slate-800"
                                                id="editModalLabel{{ $item->id }}">Edit Permintaan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="block text-sm font-medium text-slate-700 mb-1">Barang</label>
                                                <select name="barang_id" class="form-control rounded-lg w-full" required>
                                                    <option value="" hidden disabled>Pilih Barang</option>
                                                    @foreach ($barangs as $barang)
                                                        <option value="{{ $barang->id }}" {{ $item->barang_id == $barang->id ? 'selected' : '' }}>
                                                            {{ $barang->nama_barang }} (Stok: {{ $barang->stok_saat_ini }} {{ $barang->satuan }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="block text-sm font-medium text-slate-700 mb-1">Jumlah Diminta</label>
                                                <input type="number" name="jumlah_diminta" class="form-control rounded-lg w-full"
                                                    value="{{ $item->jumlah_diminta }}" min="1" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="block text-sm font-medium text-slate-700 mb-1">Keperluan</label>
                                                <textarea name="keperluan" class="form-control rounded-lg w-full" rows="3">{{ $item->keperluan }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-t border-slate-100 p-4">
                                            <button type="button"
                                                class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg font-medium hover:bg-slate-300 transition"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit"
                                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Modal Terima/Tolak Permintaan (Gudang & Admin Only) -->
                    @adminOrGudang
                        <div class="modal fade" id="terimaTolaKModal{{ $item->id }}" tabindex="-1"
                            aria-labelledby="terimaTolaKLabel{{ $item->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-xl border-0 shadow-lg">
                                    <form action="{{ route('permintaan.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header border-b border-slate-100 p-4">
                                            <h5 class="modal-title font-bold text-slate-800"
                                                id="terimaTolaKLabel{{ $item->id }}">Proses Permintaan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <input type="hidden" name="barang_id" value="{{ $item->barang_id }}">
                                            <input type="hidden" name="jumlah_diminta" value="{{ $item->jumlah_diminta }}">
                                            <input type="hidden" name="keperluan" value="{{ $item->keperluan }}">
                                            
                                            <div class="bg-slate-50 rounded-lg p-3 mb-4">
                                                <p class="text-sm text-slate-600">
                                                    <strong>{{ $item->permohonan->name }}</strong> meminta 
                                                    <strong>{{ $item->jumlah_diminta }} {{ $item->barang->satuan }}</strong>
                                                    dari <strong>{{ $item->barang->nama_barang }}</strong>
                                                </p>
                                            </div>

                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-slate-700 mb-2">Status Permintaan</label>
                                                <select name="status" id="statusSelect{{ $item->id }}" class="form-control rounded-lg w-full" required onchange="toggleJumlahField({{ $item->id }})">
                                                    <option value="" hidden disabled selected>Pilih Status</option>
                                                    <option value="disetujui">✓ Terima</option>
                                                    <option value="ditolak">✗ Tolak</option>
                                                </select>
                                            </div>

                                            <div id="jumlahField{{ $item->id }}" class="mb-4" style="display: none;">
                                                <label class="block text-sm font-medium text-slate-700 mb-1">Jumlah Disetujui</label>
                                                <input type="number" name="jumlah_disetujui" id="jumlahInput{{ $item->id }}"
                                                    class="form-control rounded-lg w-full" min="1" max="{{ $item->jumlah_diminta }}"
                                                    placeholder="Masukkan jumlah yang disetujui">
                                                <small class="text-slate-500">Maksimal: {{ $item->jumlah_diminta }} {{ $item->barang->satuan }}</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-t border-slate-100 p-4">
                                            <button type="button"
                                                class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg font-medium hover:bg-slate-300 transition"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit"
                                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition">Proses Permintaan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endadminOrGudang

                    <!-- Modal Hapus Permintaan -->
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
                                    <p>Apakah Anda yakin ingin menghapus permintaan <strong>{{ $item->no_permintaan }}</strong>?</p>
                                    <p class="text-danger small mt-2"><i class="fas fa-warning"></i> Data yang dihapus tidak dapat dikembalikan!</p>
                                </div>
                                <div class="modal-footer border-t border-slate-100 p-4">
                                    <button type="button" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg font-medium hover:bg-slate-300 transition"
                                        data-bs-dismiss="modal">Batal</button>
                                    <form method="POST" id="formHapusPermintaan{{ $item->id }}"
                                        action="{{ route('permintaan.destroy', $item->id) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 bg-danger text-white rounded-lg font-medium hover:bg-dark transition">Ya, Hapus</button>
                                    </form>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah Permintaan Baru -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-xl border-0 shadow-lg">
                <form action="{{ route('permintaan.store') }}" method="POST">
                    @csrf
                    <div class="modal-header border-b border-slate-100 p-4">
                        <h5 class="modal-title font-bold text-slate-800" id="tambahModalLabel">Tambah Permintaan Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Barang</label>
                            <select name="barang_id" class="form-control rounded-lg w-full" required>
                                <option value="" hidden disabled selected>Pilih Barang</option>
                                @foreach ($barangs as $barang)
                                    <option value="{{ $barang->id }}">
                                        {{ $barang->nama_barang }} (Stok: {{ $barang->stok_saat_ini }} {{ $barang->satuan }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Jumlah Diminta</label>
                            <input type="number" name="jumlah_diminta" class="form-control rounded-lg w-full"
                                placeholder="Masukkan jumlah" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Keperluan</label>
                            <textarea name="keperluan" class="form-control rounded-lg w-full" rows="3"
                                placeholder="Jelaskan keperluan barang..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-t border-slate-100 p-4">
                        <button type="button"
                            class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg font-medium hover:bg-slate-300 transition"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition">Simpan Permintaan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function checkPermintaanStatus(event, status) {
            if (status !== 'menunggu') {
                event.preventDefault();
                event.stopPropagation();
                alert('Permintaan sudah ' + status + '. Tidak bisa diubah lagi!');
                return false;
            }
        }

        function toggleJumlahField(itemId) {
            const status = document.getElementById('statusSelect' + itemId).value;
            const jumlahField = document.getElementById('jumlahField' + itemId);
            const jumlahInput = document.getElementById('jumlahInput' + itemId);

            if (status === 'disetujui') {
                jumlahField.style.display = 'block';
                jumlahInput.required = true;
            } else {
                jumlahField.style.display = 'none';
                jumlahInput.required = false;
            }
        }

        $(document).ready(function() {
            $('#table-permintaan').DataTable({
                responsive: true,
                pagingType: "simple_numbers",
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari permintaan...",
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
                        responsivePriority: 2,
                        targets: -1
                    }
                ]
            });
        });
    </script>
@endpush
