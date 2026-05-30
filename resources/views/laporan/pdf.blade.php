<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Inventaris</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1e293b;
            background: #fff;
        }

        /* ===== HEADER ===== */
        .header {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: #fff;
            padding: 24px 28px;
            margin-bottom: 24px;
        }
        .header-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .logo-box {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: bold;
        }
        .header h1 {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.3px;
        }
        .header .subtitle {
            font-size: 11px;
            opacity: 0.8;
            margin-top: 2px;
        }
        .header-meta {
            text-align: right;
            font-size: 10px;
            opacity: 0.85;
        }
        .header-meta strong {
            display: block;
            font-size: 12px;
            margin-bottom: 2px;
        }

        /* ===== SECTION ===== */
        .section { padding: 0 28px; margin-bottom: 22px; }

        .section-title {
            font-size: 12px;
            font-weight: 700;
            color: #4f46e5;
            border-bottom: 2px solid #e0e7ff;
            padding-bottom: 6px;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ===== STAT CARDS ===== */
        .stat-grid {
            display: table;
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px;
            margin-bottom: 4px;
        }
        .stat-grid-row { display: table-row; }
        .stat-card {
            display: table-cell;
            width: 25%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 16px;
            vertical-align: middle;
        }
        .stat-card .label {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 6px;
        }
        .stat-card .value {
            font-size: 22px;
            font-weight: 800;
            color: #1e293b;
        }
        .stat-card .accent {
            display: inline-block;
            width: 28px;
            height: 4px;
            border-radius: 2px;
            margin-top: 6px;
        }
        .accent-indigo  { background: #4f46e5; }
        .accent-amber   { background: #f59e0b; }
        .accent-sky     { background: #0ea5e9; }
        .accent-emerald { background: #10b981; }

        /* ===== TABLE ===== */
        table.report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        table.report-table thead tr {
            background: #4f46e5;
            color: #fff;
        }
        table.report-table thead th {
            padding: 9px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        table.report-table thead th.text-right { text-align: right; }
        table.report-table tbody tr:nth-child(even) { background: #f8fafc; }
        table.report-table tbody tr:nth-child(odd)  { background: #fff; }
        table.report-table tbody td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            color: #374151;
        }
        table.report-table tbody td.text-right { text-align: right; }
        table.report-table tbody td.text-center { text-align: center; }

        /* ===== BADGE ===== */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
        }
        .badge-menunggu   { background: #fef3c7; color: #92400e; }
        .badge-disetujui  { background: #d1fae5; color: #065f46; }
        .badge-ditolak    { background: #fee2e2; color: #991b1b; }
        .badge-diserahkan { background: #dbeafe; color: #1e40af; }
        .badge-masuk      { background: #d1fae5; color: #065f46; }
        .badge-keluar     { background: #fee2e2; color: #991b1b; }
        .badge-opname     { background: #e0e7ff; color: #3730a3; }
        .badge-transfer   { background: #fef3c7; color: #92400e; }

        /* ===== CRITICAL STOCK ===== */
        .critical-bar {
            display: inline-block;
            height: 8px;
            border-radius: 4px;
            background: #4f46e5;
            min-width: 4px;
        }
        .text-rose   { color: #ef4444; font-weight: 700; }
        .text-amber  { color: #f59e0b; font-weight: 700; }
        .text-green  { color: #10b981; font-weight: 700; }

        /* ===== TWO-COL LAYOUT ===== */
        .two-col { display: table; width: 100%; border-spacing: 16px 0; }
        .col      { display: table-cell; vertical-align: top; }
        .col-left  { width: 55%; }
        .col-right { width: 45%; }

        /* ===== FOOTER ===== */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 8px 28px;
            font-size: 9px;
            color: #94a3b8;
            display: flex;
            justify-content: space-between;
        }

        /* ===== SUMMARY BOX ===== */
        .summary-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 14px;
        }
        .summary-box p { font-size: 10px; color: #1e40af; line-height: 1.6; }
        .summary-box strong { font-weight: 700; }

        /* ===== PAGE BREAK ===== */
        .page-break { page-break-after: always; }
        .no-break   { page-break-inside: avoid; }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            text-align: center;
            padding: 16px;
            color: #94a3b8;
            font-style: italic;
            font-size: 10px;
        }
    </style>
</head>
<body>

    {{-- ========== HEADER ========== --}}
    <div class="header">
        <div class="header-inner">
            <div class="header-logo">
                <div class="logo-box">M</div>
                <div>
                    <div style="font-size:18px; font-weight:800; letter-spacing:-0.5px;">My-Inventory</div>
                    <div class="subtitle">Sistem Manajemen Inventaris</div>
                </div>
            </div>
            <div class="header-meta">
                <strong>LAPORAN INVENTARIS</strong>
                Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} &mdash; {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}<br>
                Dicetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB
            </div>
        </div>
    </div>

    {{-- ========== RINGKASAN EKSEKUTIF ========== --}}
    <div class="section">
        <div class="summary-box">
            <p>
                Laporan ini mencakup data inventaris untuk periode
                <strong>{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }}</strong> hingga
                <strong>{{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</strong>.
                Total terdapat <strong>{{ number_format($totalBarang) }} jenis barang</strong> di gudang,
                dengan <strong>{{ $stokMenipis }} item</strong> dalam kondisi stok kritis.
                Pada periode ini tercatat <strong>{{ number_format($totalPermintaan) }} permintaan</strong>,
                di mana <strong>{{ number_format($permintaanDiserahkan) }} permintaan</strong> telah diserahkan.
            </p>
        </div>
    </div>

    {{-- ========== STATISTIK UTAMA ========== --}}
    <div class="section">
        <div class="section-title">Statistik Utama</div>
        <div class="stat-grid">
            <div class="stat-grid-row">
                <div class="stat-card">
                    <div class="label">Total Barang</div>
                    <div class="value">{{ number_format($totalBarang) }}</div>
                    <div class="accent accent-indigo"></div>
                </div>
                <div class="stat-card">
                    <div class="label">Stok Kritis</div>
                    <div class="value">{{ number_format($stokMenipis) }}</div>
                    <div class="accent accent-amber"></div>
                </div>
                <div class="stat-card">
                    <div class="label">Total Permintaan</div>
                    <div class="value">{{ number_format($totalPermintaan) }}</div>
                    <div class="accent accent-sky"></div>
                </div>
                <div class="stat-card">
                    <div class="label">Diserahkan</div>
                    <div class="value">{{ number_format($permintaanDiserahkan) }}</div>
                    <div class="accent accent-emerald"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== STATUS PERMINTAAN ========== --}}
    @php $totalReqCheck = $statusPermintaan ? $statusPermintaan->sum() : 0; @endphp
    @if($totalReqCheck > 0)
    <div class="section no-break">
        <div class="section-title">Rekapitulasi Status Permintaan</div>
        <table class="report-table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th class="text-right">Jumlah</th>
                    <th class="text-right">Persentase</th>
                </tr>
            </thead>
            <tbody>
                @php $totalReq = $totalReqCheck; @endphp
                @foreach($statusPermintaan as $status => $count)
                <tr>
                    <td><span class="badge badge-{{ $status }}">{{ ucfirst($status) }}</span></td>
                    <td class="text-right">{{ number_format($count) }}</td>
                    <td class="text-right">{{ $totalReq > 0 ? number_format(($count / $totalReq) * 100, 1) : 0 }}%</td>
                </tr>
                @endforeach
                <tr style="background:#e0e7ff; font-weight:700;">
                    <td>TOTAL</td>
                    <td class="text-right">{{ number_format($totalReq) }}</td>
                    <td class="text-right">100%</td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    {{-- ========== TOP BARANG KELUAR + STOK KRITIS (side by side) ========== --}}
    <div class="section no-break">
        <div class="two-col">
            {{-- Top Barang Keluar --}}
            <div class="col col-left">
                <div class="section-title">Top Barang Paling Banyak Keluar</div>
                @if($topBarangKeluar->count() > 0)
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Barang</th>
                            <th>Kode</th>
                            <th class="text-right">Jml Keluar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topBarangKeluar as $i => $item)
                        <tr>
                            <td class="text-center" style="font-weight:700; color:#6366f1;">{{ $i + 1 }}</td>
                            <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                            <td style="color:#94a3b8; font-size:9px;">{{ $item->barang->kode_barang ?? '-' }}</td>
                            <td class="text-right text-rose">{{ number_format($item->total_keluar) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <div class="empty-state">Tidak ada data pada periode ini</div>
                @endif
            </div>

            {{-- Stok Kritis --}}
            <div class="col col-right">
                <div class="section-title">Barang Stok Kritis</div>
                @if($barangKritis->count() > 0)
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th class="text-right">Stok</th>
                            <th class="text-right">Min</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barangKritis as $barang)
                        <tr>
                            <td>
                                <div style="font-weight:600;">{{ Str::limit($barang->nama_barang, 25) }}</div>
                                <div style="font-size:9px; color:#94a3b8;">{{ $barang->kategori->nama_kategori ?? '-' }}</div>
                            </td>
                            <td class="text-right {{ $barang->stok_saat_ini == 0 ? 'text-rose' : 'text-amber' }}">
                                {{ $barang->stok_saat_ini }}
                            </td>
                            <td class="text-right" style="color:#64748b;">{{ $barang->stok_minimum }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <div class="empty-state">Semua stok dalam kondisi aman ✓</div>
                @endif
            </div>
        </div>
    </div>

    {{-- ========== RIWAYAT MUTASI ========== --}}
    <div class="page-break"></div>

    <div class="section">
        <div class="section-title">Riwayat Mutasi Barang ({{ $mutasiTerbaru->count() }} transaksi)</div>
        @if($mutasiTerbaru->count() > 0)
        <table class="report-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Barang</th>
                    <th>Kode</th>
                    <th>Tipe</th>
                    <th class="text-right">Jumlah</th>
                    <th class="text-right">Stok Sebelum</th>
                    <th class="text-right">Stok Sesudah</th>
                    <th>Operator</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mutasiTerbaru as $mutasi)
                <tr>
                    <td style="white-space:nowrap;">
                        {{ \Carbon\Carbon::parse($mutasi->created_at)->format('d/m/Y') }}<br>
                        <span style="color:#94a3b8; font-size:9px;">{{ \Carbon\Carbon::parse($mutasi->created_at)->format('H:i') }}</span>
                    </td>
                    <td style="font-weight:600;">{{ Str::limit($mutasi->barang->nama_barang ?? '-', 22) }}</td>
                    <td style="color:#94a3b8; font-size:9px;">{{ $mutasi->barang->kode_barang ?? '-' }}</td>
                    <td><span class="badge badge-{{ $mutasi->tipe }}">{{ ucfirst($mutasi->tipe) }}</span></td>
                    <td class="text-right {{ in_array($mutasi->tipe, ['masuk','transfer']) ? 'text-green' : 'text-rose' }}">
                        {{ in_array($mutasi->tipe, ['masuk','transfer']) ? '+' : '-' }}{{ number_format($mutasi->jumlah) }}
                    </td>
                    <td class="text-right" style="color:#64748b;">{{ number_format($mutasi->stok_sebelum) }}</td>
                    <td class="text-right" style="font-weight:700;">{{ number_format($mutasi->stok_sesudah) }}</td>
                    <td style="font-size:9px;">{{ Str::limit($mutasi->user->name ?? '-', 15) }}</td>
                    <td style="color:#64748b; font-size:9px;">{{ Str::limit($mutasi->keterangan, 30) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <div class="empty-state">Tidak ada data mutasi pada periode ini</div>
        @endif
    </div>

    {{-- ========== FOOTER ========== --}}
    <div class="footer">
        <span>My-Inventory &mdash; Sistem Manajemen Inventaris</span>
        <span>Laporan dibuat otomatis pada {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }} WIB</span>
    </div>

</body>
</html>