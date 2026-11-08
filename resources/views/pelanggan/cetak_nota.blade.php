<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pesanan #{{ $pesanan->kode_pesanan }} - ArsyClean Laundry</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            color: #1e293b;
            padding: 20px;
            background: #fff;
            max-width: 600px;
            margin: auto;
        }
        .receipt-card {
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 24px;
        }
        .header {
            text-align: center;
            border-bottom: 2px dashed #94a3b8;
            padding-bottom: 16px;
            margin-bottom: 16px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 900;
            color: #0f2a5c;
        }
        .header p {
            margin: 3px 0 0;
            font-size: 12px;
            color: #64748b;
        }
        .section-title {
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            color: #475569;
            margin-bottom: 6px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
            font-size: 12px;
        }
        .info-item span {
            display: block;
            color: #64748b;
            font-size: 11px;
        }
        .info-item strong {
            color: #0f2a5c;
            font-size: 13px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
            font-size: 12px;
        }
        th, td {
            padding: 8px 10px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .total-row td {
            font-size: 15px;
            font-weight: 900;
            color: #0f2a5c;
            border-top: 2px solid #0f2a5c;
            border-bottom: none;
            padding-top: 12px;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: bold;
        }
        .badge-lunas { background: #dcfce7; color: #166534; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .footer-note {
            text-align: center;
            font-size: 11px;
            color: #64748b;
            margin-top: 24px;
            border-top: 2px dashed #94a3b8;
            padding-top: 16px;
        }
        .no-print {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            justify-content: center;
        }
        .btn {
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 13px;
            cursor: pointer;
            border: none;
        }
        .btn-print { background: #0f2a5c; color: #fff; }
        .btn-close { background: #e2e8f0; color: #334155; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
            .receipt-card { border: none; padding: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn btn-print">
            🖨️ Cetak Struk / Nota PDF
        </button>
        <button onclick="window.close()" class="btn btn-close">
            ✕ Tutup
        </button>
    </div>

    <div class="receipt-card">
        <div class="header">
            <h1>ARSYCLEAN LAUNDRY</h1>
            <p>Solusi Laundry Bersih, Cepat &amp; Terpercaya</p>
            <p>WhatsApp CS: 0812-3456-7890 &bull; 24 Jam</p>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <span>Kode Pesanan:</span>
                <strong>#{{ $pesanan->kode_pesanan }}</strong>
            </div>
            <div class="info-item">
                <span>Tanggal Pesanan:</span>
                <strong>{{ $pesanan->tanggal_pesanan ? $pesanan->tanggal_pesanan->format('d M Y') : '-' }}</strong>
            </div>
            <div class="info-item">
                <span>Nama Pelanggan:</span>
                <strong>{{ $pesanan->user->nama ?? 'Pelanggan' }}</strong>
            </div>
            <div class="info-item">
                <span>Nomor WhatsApp:</span>
                <strong>{{ $pesanan->user->no_hp ?? '-' }}</strong>
            </div>
            <div class="info-item">
                <span>Status Pengerjaan:</span>
                <strong>{{ $pesanan->status_laundry ?? 'Menunggu Antrean' }}</strong>
            </div>
            <div class="info-item">
                <span>Status Pembayaran:</span>
                @php
                    $statusBayar = $pesanan->pembayaran?->status_pembayaran ?? 'Pending';
                @endphp
                <strong class="badge {{ $statusBayar === 'Lunas' ? 'badge-lunas' : 'badge-pending' }}">
                    {{ $statusBayar }} ({{ $pesanan->metode_pembayaran }})
                </strong>
            </div>
        </div>

        <div style="margin-bottom: 12px; font-size: 12px;">
            <span style="color: #64748b; font-size: 11px; display: block;">Alamat Penjemputan / Pengantaran:</span>
            <strong>{{ $pesanan->alamat_penjemputan ?? '-' }}</strong>
        </div>

        @if(!empty($pesanan->catatan))
        <div style="margin-bottom: 14px; font-size: 12px; background: #f8fafc; padding: 10px; border-radius: 8px;">
            <span style="color: #64748b; font-size: 11px; display: block;">Catatan Khusus:</span>
            <span style="white-space: pre-line;">{{ $pesanan->catatan }}</span>
        </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>Layanan Laundry</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Tarif</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pesanan->layanan as $lay)
                <tr>
                    <td><b>{{ $lay->nama_layanan }}</b></td>
                    <td class="text-right">{{ $lay->pivot->jumlah }} {{ $lay->satuan }}</td>
                    <td class="text-right">Rp {{ number_format($lay->pivot->harga_satuan, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($lay->pivot->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="3">TOTAL PEMBAYARAN:</td>
                    <td class="text-right">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        @if($pesanan->pengiriman)
        <div style="font-size: 12px; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 10px; border-radius: 8px; margin-top: 10px;">
            <strong>🚚 Informasi Pengiriman Kurir:</strong>
            <div>Kurir: {{ $pesanan->pengiriman->nama_kurir }} ({{ $pesanan->pengiriman->no_hp_kurir }})</div>
            <div>Status: <b>{{ $pesanan->pengiriman->status_pengiriman }}</b> (No. Resi: {{ $pesanan->pengiriman->no_resi }})</div>
        </div>
        @endif

        <div class="footer-note">
            <p>Terima kasih telah mempercayakan pakaian Anda kepada <b>ArsyClean Laundry</b>.</p>
            <p>Simpan struk ini sebagai bukti transaksi resmi Anda.</p>
        </div>
    </div>

</body>
</html>
