<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Rekapitulasi Presensi</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.4;
            margin: 0;
            padding: 10px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .header h2 {
            margin: 0 0 4px 0;
            font-size: 16px;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 2px 0;
            color: #64748b;
            font-size: 11px;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 15px;
        }
        .meta-table td {
            font-size: 10.5px;
            padding: 2px 4px;
            border: none;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: bold;
            text-align: center;
            border: 1px solid #cbd5e1;
            padding: 6px 4px;
            font-size: 10px;
            text-transform: uppercase;
        }
        .data-table td {
            border: 1px solid #cbd5e1;
            padding: 5px 6px;
            font-size: 10px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge-status {
            font-weight: bold;
            font-size: 9px;
            padding: 2px 6px;
            border-radius: 4px;
        }
        .summary-box {
            width: 100%;
            margin-bottom: 25px;
        }
        .summary-card {
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            padding: 8px 12px;
            display: inline-block;
            margin-right: 10px;
            font-size: 10.5px;
        }
        .signature-section {
            width: 100%;
            margin-top: 30px;
        }
        .signature-box {
            float: right;
            width: 200px;
            text-align: center;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Rekapitulasi Presensi</h2>
        <p>Aplikasi Presensi GPS Geofencing</p>
    </div>

    <table class="meta-table">
        <tr>
            <td style="width: 15%;"><strong>Mode Laporan</strong></td>
            <td style="width: 35%;">: {{ ucfirst($mode ?? 'Bulanan') }}</td>
            <td style="width: 15%;"><strong>Filter Pegawai</strong></td>
            <td style="width: 35%;">: {{ isset($pegawaiFilter) && $pegawaiFilter ? $pegawaiFilter->nama . ' (NRG: ' . $pegawaiFilter->nrg . ')' : 'Semua Pegawai' }}</td>
        </tr>
        <tr>
            <td><strong>Periode</strong></td>
            <td>: {{ $labelPeriode ?? ($start . ' s/d ' . $end) }}</td>
            <td><strong>Filter Kantor</strong></td>
            <td>: {{ isset($lokasiFilter) && $lokasiFilter ? $lokasiFilter->nama_lokasi : 'Semua Kantor' }}</td>
        </tr>
        <tr>
            <td><strong>Dicetak Pada</strong></td>
            <td colspan="3">: {{ now()->locale('id')->translatedFormat('d F Y, H:i') }} WIB</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">#</th>
                <th style="text-align: left;">Pegawai</th>
                <th style="width: 80px; text-align: left;">Kantor</th>
                <th style="width: 65px;">Tanggal</th>
                <th style="width: 60px;">Masuk</th>
                <th style="width: 55px;">Jarak Masuk</th>
                <th style="width: 60px;">Pulang</th>
                <th style="width: 55px;">Jarak Pulang</th>
                <th style="width: 65px;">Status</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($presensi as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>
                    <strong>{{ $item->pegawai->nama ?? '-' }}</strong><br>
                    <span style="color: #64748b; font-size: 9px;">NRG: {{ $item->pegawai->nrg ?? '-' }}</span>
                </td>
                <td>{{ $item->pegawai->lokasiPresensi->nama_lokasi ?? '-' }}</td>
                <td class="text-center">{{ $item->tanggal_masuk->format('d/m/Y') }}</td>
                <td class="text-center" style="color: #2563eb; font-weight: bold;">{{ $item->jam_masuk }}</td>
                <td class="text-center">{{ !is_null($item->jarak_masuk) ? $item->jarak_masuk . ' m' : '-' }}</td>
                <td class="text-center" style="color: #b45309; font-weight: bold;">{{ $item->jam_keluar ?? '-' }}</td>
                <td class="text-center">{{ !is_null($item->jarak_keluar) ? $item->jarak_keluar . ' m' : '-' }}</td>
                <td class="text-center">
                    @if($item->jam_keluar)
                        <span style="color: #16a34a; font-weight: bold;">Selesai</span>
                    @else
                        <span style="color: #d97706; font-weight: bold;">Belum Pulang</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center" style="padding: 15px; color: #64748b;">
                    Tidak ada catatan presensi pada periode ini.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="summary-box">
        <div class="summary-card">
            <strong>Total Kehadiran:</strong> {{ $presensi->count() }} data
        </div>
        <div class="summary-card">
            <strong>Presensi Lengkap:</strong> {{ $presensi->whereNotNull('jam_keluar')->count() }}
        </div>
        <div class="summary-card">
            <strong>Belum Pulang:</strong> {{ $presensi->whereNull('jam_keluar')->count() }}
        </div>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <p style="margin-bottom: 50px;">Mengetahui,<br><strong>Administrator</strong></p>
            <p style="margin: 0; text-decoration: underline; font-weight: bold;">( Administrator )</p>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>
