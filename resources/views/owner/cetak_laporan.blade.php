<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan & Omzet - ArsyClean Laundry</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            color: #1e293b;
            padding: 24px;
            background: #fff;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0f2a5c;
            padding-bottom: 16px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0 0 6px;
            font-size: 22px;
            font-weight: 800;
            color: #0f2a5c;
            letter-spacing: -0.5px;
        }
        .header p {
            margin: 2px 0;
            color: #475569;
            font-size: 12px;
        }
        .meta-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
            font-size: 12px;
            color: #334155;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 9px 12px;
            text-align: left;
        }
        th {
            background-color: #f1f5f9;
            color: #0f2a5c;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-box {
            margin-top: 24px;
            padding: 16px;
            background-color: #f8fafc;
            border: 2px solid #0f2a5c;
            border-radius: 8px;
            text-align: right;
            font-size: 15px;
            font-weight: 800;
            color: #0f2a5c;
        }
        .actions-bar {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 13px;
            cursor: pointer;
            border: none;
            text-decoration: none;
        }
        .btn-print {
            background-color: #0f2a5c;
            color: #ffffff;
        }
        .btn-print:hover {
            background-color: #1e3a8a;
        }
        .btn-close {
            background-color: #e2e8f0;
            color: #334155;
        }
        .btn-close:hover {
            background-color: #cbd5e1;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
            .total-box {
                border-color: #000;
            }
        }
    </style>
</head>
<body>

    <div class="actions-bar no-print">
        <button onclick="window.print()" class="btn btn-print">
            🖨️ Cetak Dokumen / Simpan PDF
        </button>
        <button onclick="window.close()" class="btn btn-close">
            ✕ Tutup Jendela
        </button>
    </div>

    <div class="header">
        <h1>ARSYCLEAN LAUNDRY MANAGEMENT</h1>
        <p><b>LAPORAN KEUANGAN, OMZET &amp; TRANSAKSI LUNAS</b></p>
        <p>Layanan Laundry Koin, Antar Jemput, Reguler &amp; Dry Clean</p>
    </div>

    <div class="meta-info">
        <div>
            <strong>Periode Laporan:</strong>
            @if(request('tanggal_mulai') && request('tanggal_selesai'))
                {{ date('d/m/Y', strtotime(request('tanggal_mulai'))) }} s/d {{ date('d/m/Y', strtotime(request('tanggal_selesai'))) }}
            @elseif(request('tanggal_mulai'))
                Mulai {{ date('d/m/Y', strtotime(request('tanggal_mulai'))) }}
            @else
                Semua Riwayat Transaksi (Keseluruhan)
            @endif
        </div>
        <div>
            <strong>Tanggal Cetak:</strong> {{ date('d-m-Y H:i:s') }} WIB
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 40px;">No</th>
                <th>Kode Bayar</th>
                <th>Kode Pesanan</th>
                <th>Nama Pelanggan</th>
                <th>Rincian Layanan</th>
                <th>Waktu Transaksi</th>
                <th class="text-right">Jumlah Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pembayarans as $index => $bayar)
            @php
                $layananNama = $bayar->pesanan?->layanan ? $bayar->pesanan->layanan->pluck('nama_layanan')->implode(', ') : '-';
                $namaPelanggan = $bayar->pesanan?->user?->nama ?? 'Pelanggan';
            @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td style="font-family: monospace; font-weight: bold;">{{ $bayar->kode_pembayaran }}</td>
                <td style="font-family: monospace; font-weight: bold;">#{{ $bayar->pesanan->kode_pesanan ?? '-' }}</td>
                <td><b>{{ $namaPelanggan }}</b></td>
                <td>{{ $layananNama }}</td>
                <td>{{ $bayar->created_at ? $bayar->created_at->format('d/m/Y H:i') : '-' }}</td>
                <td class="text-right" style="font-weight: bold;">
                    Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 24px; color: #64748b;">
                    Tidak ada data transaksi pembayaran lunas pada filter periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total-box">
        TOTAL OMZET TRANSAKSI MASUK: Rp {{ number_format($totalOmzet, 0, ',', '.') }}
        <div style="font-size: 11px; font-weight: normal; color: #475569; margin-top: 4px;">
            Total Transaksi: {{ $pembayarans->count() }} Transaksi Tercatat Lunas
        </div>
    </div>

    <div style="margin-top: 40px; display: flex; justify-content: flex-end;">
        <div style="text-align: center; min-width: 200px;">
            <p style="margin-bottom: 60px;">Mengetahui,<br><strong>Pemilik Usaha (Owner)</strong></p>
            <p style="border-top: 1px solid #334155; padding-top: 4px; font-weight: bold;">ArsyClean Laundry</p>
        </div>
    </div>

</body>
</html>
