@extends('layouts.app')

@section('title', 'Data Barang')
@section('header_title', 'Master Data Barang')
@section('header_subtitle', 'Kelola semua stok aset dan inventaris perusahaan.')

@section('content')
    <div class="flex justify-end mb-6">
        <button data-bs-toggle="modal" data-bs-target="#tambahModal"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-medium transition-all shadow-md flex items-center justify-center gap-2">
            <i class="fas fa-plus"></i> Tambah Barang
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <table id="table-barang" class="responsive whitespace-nowrap w-full text-left border-collapse nowrap text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500">
                    <th class="px-4 py-4 font-semibold">No</th>
                    <th class="px-4 py-4 font-semibold">Nama Barang</th>
                    <th class="px-4 py-4 font-semibold">Nama Supplier</th>
                    <th class="px-4 py-4 font-semibold">Kategori</th>
                    <th class="px-4 py-4 font-semibold">Stok</th>
                    <th class="px-4 py-4 font-semibold">Lokasi Rak</th>
                    <th class="px-4 py-4 font-semibold">Kondisi</th>
                    <th class="px-4 py-4 font-semibold">Kode Barang</th>
                    <th class="px-4 py-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barangs as $barang)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-700 ps-6">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">
                            <div class="font-bold text-slate-800">{{ $barang->nama_barang }}</div>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $barang->supplier->nama_supplier }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $barang->kategori->nama_kategori }}</td>
                        <td class="px-4 py-3 text-center font-bold text-slate-800">{{ $barang->stok_saat_ini }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $barang->lokasi_rak }}</td>
                        <td class="px-4 py-3">
                            @php
                                $statusClass = '';
                                switch ($barang->kondisi) {
                                    case 'baik':
                                        $statusClass = 'bg-emerald-100 text-emerald-700';
                                        break;
                                    case 'rusak':
                                        $statusClass = 'bg-rose-100 text-rose-700';
                                        break;
                                    default:
                                        $statusClass = 'bg-slate-100 text-slate-700';
                                }
                            @endphp
                            <span
                                class="px-3 py-1 rounded-lg text-xs font-bold {{ $statusClass }}">{{ $barang->kondisi }}</span>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $barang->kode_barang }}</td>
                        <td class="px-4 py-3 text-right">
                            <button data-bs-toggle="modal" data-bs-target="#editModal{{ $barang->id }}"
                                class="text-indigo-600 hover:text-indigo-800 mr-2">
                                <i class="fas fa-pen"></i>
                            </button>
                            <button data-bs-toggle="modal" data-bs-target="#modalHapusBarang{{ $barang->id }}"
                                class="text-rose-600 hover:text-rose-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty($barangs)
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fas fa-box-open text-4xl text-slate-300"></i>
                                    <p>Belum ada data barang tersedia.</p>
                                </div>
                            </td>
                        </tr>
                    @endempty

                    <div class="modal fade" id="editModal{{ $barang->id }}" tabindex="-1"
                        aria-labelledby="editModalLabel{{ $barang->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-xl border-0 shadow-lg">
                                <form action="{{ route('barang.update', $barang->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header border-b border-slate-100 p-4">
                                        <h5 class="modal-title font-bold text-slate-800"
                                            id="editModalLabel{{ $barang->id }}">Edit
                                            Barang</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body row p-4">
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Barang</label>
                                            <input type="text" name="nama_barang" class="form-control rounded-lg w-full"
                                                placeholder="Contoh: Pensil" required value="{{ $barang->nama_barang }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
                                            <select name="kategori_id" class="form-control rounded-lg w-full" required>
                                                <option value="" hidden disabled selected>Pilih Kategori</option>
                                                @foreach ($kategoris as $kat)
                                                    <option value="{{ $kat->id }}" {{ $barang->kategori_id == $kat->id ? 'selected' : '' }}>
                                                        {{ $kat->nama_kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Supplier</label>
                                            <select name="supplier_id" class="form-control rounded-lg w-full" required>
                                                <option value="" hidden disabled selected>Pilih Supplier</option>
                                                @foreach ($suppliers as $sup)
                                                    <option value="{{ $sup->id }}" {{ $barang->supplier_id == $sup->id ? 'selected' : '' }}>
                                                        {{ $sup->nama_supplier }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Satuan</label>
                                            <select name="satuan" id="satuan" class="form-control rounded-lg w-full"
                                                required>
                                                <option value="" hidden disabled selected>Pilih Satuan</option>
                                                <option value="Pcs" {{ $barang->satuan == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                                                <option value="Box" {{ $barang->satuan == 'Box' ? 'selected' : '' }}>Box</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Stok Awal</label>
                                            <input type="number" name="stok_saat_ini"
                                                class="form-control rounded-lg w-full" value="{{ $barang->stok_saat_ini }}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Stok Min</label>
                                            <input type="number" name="stok_minimum" class="form-control rounded-lg w-full"
                                                value="{{ $barang->stok_minimum }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Lokasi Rak</label>
                                            <input type="text" name="lokasi_rak"
                                                class="form-control rounded-lg w-full" placeholder="Contoh: Rak A1"
                                                value="{{ $barang->lokasi_rak }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Kondisi</label>
                                            <select name="kondisi" class="form-control rounded-lg w-full" required>
                                                <option value="" hidden disabled selected>Pilih Kondisi</option>
                                                <option value="baik" {{ $barang->kondisi == 'baik' ? 'selected' : '' }}>Baik</option>
                                                <option value="rusak" {{ $barang->kondisi == 'rusak' ? 'selected' : '' }}>Rusak</option>
                                                <option value="kadaluarsa" {{ $barang->kondisi == 'kadaluarsa' ? 'selected' : '' }}>Kadaluarsa</option>
                                            </select>
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

                    <div class="modal fade" id="modalHapusBarang{{ $barang->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white"
                                        data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Apakah Anda yakin ingin menghapus barang
                                        "<strong>{{ $barang->nama_barang }}</strong>"?</p>
                                    <p class="text-danger small">Data yang dihapus tidak dapat dikembalikan!</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Batal</button>
                                    <form method="POST" id="formHapusBarang{{ $barang->id }}"
                                        action="{{ route('barang.destroy', $barang->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-xl border-0 shadow-lg">
                <form action="{{ route('barang.store') }}" method="POST">
                    @csrf
                    <div class="modal-header border-b border-slate-100 p-4">
                        <h5 class="modal-title font-bold text-slate-800" id="tambahModalLabel">Tambah Barang Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body row p-4">
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Barang</label>
                            <input type="text" name="nama_barang" class="form-control rounded-lg w-full"
                                placeholder="Contoh: Pensil" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
                            <select name="kategori_id" class="form-control rounded-lg w-full" required>
                                <option value="" hidden disabled selected>Pilih Kategori</option>
                                @foreach ($kategoris as $kat)
                                    <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Supplier</label>
                            <select name="supplier_id" class="form-control rounded-lg w-full" required>
                                <option value="" hidden disabled selected>Pilih Supplier</option>
                                @foreach ($suppliers as $sup)
                                    <option value="{{ $sup->id }}">{{ $sup->nama_supplier }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Satuan</label>
                            <select name="satuan" id="satuan" class="form-control rounded-lg w-full" required>
                                <option value="" hidden disabled selected>Pilih Satuan</option>
                                <option value="Pcs">Pcs</option>
                                <option value="Box">Box</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Stok Awal</label>
                            <input type="number" name="stok_saat_ini" class="form-control rounded-lg w-full"
                                value="0" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Stok Min</label>
                            <input type="number" name="stok_minimum" class="form-control rounded-lg w-full"
                                value="5" min="2" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Lokasi Rak</label>
                            <input type="text" name="lokasi_rak" class="form-control rounded-lg w-full"
                                placeholder="Contoh: Rak A1" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Kondisi</label>
                            <select name="kondisi" class="form-control rounded-lg w-full" required>
                                <option value="" hidden disabled selected>Pilih Kondisi</option>
                                <option value="baik">Baik</option>
                                <option value="rusak">Rusak</option>
                                <option value="kadaluarsa">Kadaluarsa</option>
                            </select>
                        </div>
                        <div class="modal-footer border-t border-slate-100 p-4">
                            <button type="button"
                                class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg font-medium hover:bg-slate-300 transition"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition">Simpan
                                Barang</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#table-barang').DataTable({
                responsive: true,
                pagingType: "simple_numbers",
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari ...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    paginate: {
                        previous: "‹",
                        next: "›"
                    },
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
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
