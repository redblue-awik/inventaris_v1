@extends('layouts.app')

@section('title', 'Data Barang')
@section('header_title', 'Master Data Barang')
@section('header_subtitle', 'Kelola semua stok aset dan inventaris perusahaan.')

@section('content')
    <div class="flex justify-end mb-6">
        <button
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-medium transition-all shadow-md flex items-center justify-center gap-2">
            <i class="fas fa-plus"></i> Tambah Barang
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <table id="table-barang" class="w-full text-left border-collapse nowrap text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500">
                    <th class="px-4 py-4 font-semibold">No</th>
                    <th class="px-4 py-4 font-semibold">Info Barang</th>
                    <th class="px-4 py-4 font-semibold">Kategori</th>
                    <th class="px-4 py-4 font-semibold text-center">Stok</th>
                    <th class="px-4 py-4 font-semibold">Status</th>
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
                        <td class="px-4 py-3 text-slate-600">Elektronik</td>
                        <td class="px-4 py-3 text-center font-bold text-slate-800">{{ $barang->jumlah }}</td>
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
                        <td class="px-4 py-3 text-right">
                            <button class="text-indigo-600 hover:text-indigo-800 mr-2"><i class="fas fa-pen"></i></button>
                            <button class="text-rose-600 hover:text-rose-800"><i class="fas fa-trash"></i></button>
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
                @endforeach
            </tbody>
        </table>
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
                    orderable: false,
                    targets: -1
                }]
            });
        });
    </script>
@endpush
