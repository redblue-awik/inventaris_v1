@extends('layouts.app')

@section('title', 'Data Supplier')
@section('header_title', 'Master Data Supplier')
@section('header_subtitle', 'Kelola informasi supplier barang inventaris.')

@section('content')
    <div class="flex justify-end mb-6">
        <button data-bs-toggle="modal" data-bs-target="#tambahModal"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-medium transition-all shadow-md flex items-center justify-center gap-2">
            <i class="fas fa-plus"></i> Tambah Supplier
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <table id="table-supplier" class="responsive nowrap whitespace-nowrap w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500">
                    <th class="px-4 py-4 font-semibold">No</th>
                    <th class="px-4 py-4 font-semibold">Nama Supplier</th>
                    <th class="px-4 py-4 font-semibold">Kontak Person</th>
                    <th class="px-4 py-4 font-semibold">Telepon</th>
                    <th class="px-4 py-4 font-semibold">Email</th>
                    <th class="px-4 py-4 font-semibold">Alamat</th>
                    <th class="px-4 py-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suppliers as $item)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-700 cursor-pointer">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 font-bold text-slate-800">{{ $item->nama_supplier }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->kontak_person ?? '-' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->telepon ?? '-' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->email ?? '-' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->alamat ?? '-' }}</td>
                        <td class="px-4 py-3 text-right">
                            <button data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}"
                                class="text-indigo-600 hover:text-indigo-800 mr-2">
                                <i class="fas fa-pen"></i>
                            </button>
                            <button data-bs-toggle="modal" data-bs-target="#modalHapusSupplier{{ $item->id }}"
                                class="text-rose-600 hover:text-rose-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1"
                        aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-xl border-0 shadow-lg">
                                <form action="{{ route('supplier.update', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header border-b border-slate-100 p-4">
                                        <h5 class="modal-title font-bold text-slate-800"
                                            id="editModalLabel{{ $item->id }}">Edit Supplier</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama
                                                Supplier</label>
                                            <input type="text" name="nama_supplier" value="{{ $item->nama_supplier }}"
                                                class="form-control rounded-lg w-full" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Kontak
                                                Person</label>
                                            <input type="text" name="kontak_person" value="{{ $item->kontak_person }}"
                                                class="form-control rounded-lg w-full">
                                        </div>
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Telepon</label>
                                            <input type="text" name="telepon" value="{{ $item->telepon }}"
                                                class="form-control rounded-lg w-full">
                                        </div>
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                                            <input type="email" name="email" value="{{ $item->email }}"
                                                class="form-control rounded-lg w-full">
                                        </div>
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Alamat</label>
                                            <textarea name="alamat" class="form-control rounded-lg w-full" rows="3">{{ $item->alamat }}</textarea>
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

                    <div class="modal fade" id="modalHapusSupplier{{ $item->id }}" tabindex="-1">
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
                                    <p>Apakah Anda yakin ingin menghapus supplier
                                        "<strong>{{ $item->nama_supplier }}</strong>"?</p>
                                    <p class="text-danger small">Data yang dihapus tidak dapat dikembalikan!</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <form method="POST" id="formHapusSupplier{{ $item->id }}"
                                        action="{{ route('supplier.destroy', $item->id) }}">
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
                <form action="{{ route('supplier.store') }}" method="POST">
                    @csrf
                    <div class="modal-header border-b border-slate-100 p-4">
                        <h5 class="modal-title font-bold text-slate-800" id="tambahModalLabel">Tambah Supplier Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Supplier</label>
                            <input type="text" name="nama_supplier" class="form-control rounded-lg w-full" required
                                placeholder="Contoh: Elektronik">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Kontak Person</label>
                            <input type="text" name="kontak_person" class="form-control rounded-lg w-full"
                                placeholder="Contoh: Nama Anda">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Telepon</label>
                            <input type="text" name="telepon" class="form-control rounded-lg w-full"
                                placeholder="Contoh: 081234567890">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                            <input type="email" name="email" class="form-control rounded-lg w-full"
                                placeholder="Contoh: supplier@example.com">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Alamat</label>
                            <textarea name="alamat" class="form-control rounded-lg w-full" rows="3" placeholder="Opsional..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-t border-slate-100 p-4">
                        <button type="button"
                            class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg font-medium hover:bg-slate-300 transition"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition">Simpan
                            Supplier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#table-supplier').DataTable({
                responsive: true,
                pagingType: "simple_numbers",
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari supplier...",
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
