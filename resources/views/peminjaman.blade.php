@extends('layouts.app')

@section('title', 'Peminjaman')
@section('header_title', 'Sirkulasi Peminjaman')
@section('header_subtitle', 'Pantau aktivitas peminjaman dan pengembalian barang.')

@section('content')
    <div class="flex justify-end mb-6">
        <button
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-medium transition-all shadow-md flex items-center gap-2 text-sm">
            <i class="fas fa-plus"></i> Form Pinjam
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <table id="table-peminjaman" class="w-full text-left border-collapse nowrap text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500">
                    <th class="px-4 py-4 font-semibold">No</th>
                    <th class="px-4 py-4 font-semibold">Peminjam</th>
                    <th class="px-4 py-4 font-semibold">Barang</th>
                    <th class="px-4 py-4 font-semibold">Tgl Pinjam</th>
                    <th class="px-4 py-4 font-semibold">Tgl Kembali</th>
                    <th class="px-4 py-4 font-semibold">Status</th>
                    <th class="px-4 py-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($peminjamans as $peminjaman)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-700 ps-6">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">
                            <div class="font-bold text-slate-800">{{ $peminjaman->nama_peminjam }}</div>
                        </td>
                        <td class="px-4 py-3 font-medium text-slate-700">{{ $peminjaman->nama_barang }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $peminjaman->tanggal_pinjam }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $peminjaman->tanggal_kembali }}</td>
                        <td class="px-4 py-3"><span
                                class="px-3 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-bold">Dipinjam</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button
                                class="text-sm bg-indigo-50 text-indigo-600 hover:bg-indigo-100 px-3 py-1.5 rounded-lg font-medium">Proses
                                Kembali</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#table-peminjaman').DataTable({
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
                    orderable: false,
                    targets: -1
                }]
            });
        });
    </script>
@endpush
