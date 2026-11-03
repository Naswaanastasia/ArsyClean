<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan & Omzet - ArsyClean</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 2px 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total-box { margin-top: 20px; text-align: right; font-size: 14px; font-weight: bold; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 15px;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #d97706; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
            🖨️ Cetak / Simpan PDF
        </button>
        <button onclick="window.close()" style="padding: 8px 16px; background: #6b7280; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 5px;">
            Tutup
        </button>
    </div>

    <div class="header">
        <h1>LAPORAN KEUANGAN & OMZET ARSYCLEAN</h1>
        <p>Dicetak Pada Tanggal: {{ date('d-m-Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Pembayaran</th>
                <th>Nama Pelanggan</th>
                <th>Layanan</th>
                <th>Tanggal Transaksi</th>
                <th>Jumlah Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pembayarans as $index => $bayar)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>#{{ $bayar->id_pembayaran }}</td>
                <td>{{ $bayar->pesanan->user->name ?? 'Pelanggan' }}</td>
                <td>{{ $bayar->pesanan->layanan->nama_layanan ?? '-' }}</td>
                <td>{{ $bayar->created_at->format('d-m-Y H:i') }}</td>
                <td>Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">Tidak ada data transaksi lunas pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total-box">
        TOTAL OMZET MASUK: Rp {{ number_format($totalOmzet, 0, ',', '.') }}
    </div>

</body>
</html>