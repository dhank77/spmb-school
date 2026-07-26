<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #1c1b1f;
            background: #fff;
            padding: 32px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid #6750a4;
        }
        .school-name { font-size: 20px; font-weight: bold; color: #6750a4; }
        .school-sub  { font-size: 11px; color: #49454f; margin-top: 2px; }

        .badge {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .badge-unpaid { background: #b3261e; color: #fff; }
        .badge-paid   { background: #2e7d32; color: #fff; }

        h1 { font-size: 16px; font-weight: bold; margin-bottom: 4px; }
        .label { font-size: 10px; color: #49454f; }
        .value { font-size: 11px; font-weight: 600; }

        .meta-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .meta-row { display: table-row; }
        .meta-cell {
            display: table-cell;
            padding: 4px 0;
            width: 50%;
        }

        .dates-box {
            background: #f7f2fa;
            border: 1px solid #cac4d0;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 20px;
            display: table;
            width: 100%;
        }
        .date-cell { display: table-cell; width: 50%; }
        .date-label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.05em; color: #49454f; margin-bottom: 2px; }
        .date-value { font-size: 12px; font-weight: 600; }
        .date-value-due { color: #b3261e; }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        table.items thead tr {
            background: #f7f2fa;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.06em;
            color: #49454f;
        }
        table.items th { padding: 8px 12px; text-align: left; }
        table.items th:last-child { text-align: right; }
        table.items td { padding: 10px 12px; border-bottom: 1px solid #f3edf7; }
        table.items td:last-child { text-align: right; font-weight: 500; }

        .total-row td {
            background: #eaddff;
            color: #21005d;
            font-weight: bold;
            font-size: 13px;
            border-bottom: none !important;
        }
        .total-row td:first-child { border-radius: 6px 0 0 6px; }
        .total-row td:last-child  { border-radius: 0 6px 6px 0; }

        .footer {
            margin-top: 32px;
            padding-top: 16px;
            border-top: 1px solid #cac4d0;
            font-size: 10px;
            color: #49454f;
            text-align: center;
        }
        .paid-note {
            background: #e8f5e9;
            border: 1px solid #c8e6c9;
            color: #1b5e20;
            border-radius: 6px;
            padding: 8px 12px;
            margin-bottom: 16px;
            font-size: 11px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div>
            <div class="school-name">Hitech School</div>
            <div class="school-sub">Sistem Penerimaan Murid Baru (SPMB)</div>
        </div>
        <div>
            @if($isPaid)
                <span class="badge badge-paid">LUNAS</span>
            @else
                <span class="badge badge-unpaid">BELUM BAYAR</span>
            @endif
        </div>
    </div>

    <h1>Tagihan Pendaftaran</h1>
    <p class="label" style="margin-bottom:16px;">{{ $user->name }}</p>

    <div class="meta-grid">
        <div class="meta-row">
            <div class="meta-cell">
                <div class="label">Nomor Tagihan</div>
                <div class="value">INV/SPMB/{{ date('Y') }}/{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</div>
            </div>
            @if($user->registration_number)
            <div class="meta-cell">
                <div class="label">No. Pendaftaran</div>
                <div class="value">{{ $user->registration_number }}</div>
            </div>
            @endif
        </div>
    </div>

    <div class="dates-box">
        <div class="date-cell">
            <div class="date-label">Tanggal Terbit</div>
            <div class="date-value">{{ now()->translatedFormat('d F Y') }}</div>
        </div>
        @if(!$isPaid)
        <div class="date-cell">
            <div class="date-label">Jatuh Tempo</div>
            <div class="date-value date-value-due">{{ now()->addDays(3)->translatedFormat('d F Y') }}</div>
        </div>
        @else
        <div class="date-cell">
            <div class="date-label">Metode Pembayaran</div>
            <div class="date-value">{{ $user->payment_method ?? '-' }}</div>
        </div>
        @endif
    </div>

    @if($isPaid)
    <div class="paid-note">
        ✓ Pembayaran telah dikonfirmasi. Dokumen ini adalah bukti pembayaran yang sah.
    </div>
    @endif

    <table class="items">
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Biaya Pendaftaran SPMB Hitech School</td>
                <td>Rp {{ number_format($baseFee, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kode Unik &amp; Fee</td>
                <td>Rp {{ number_format($uniqueCode, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td>{{ $isPaid ? 'Total Dibayarkan' : 'Total Pembayaran' }}</td>
                <td>Rp {{ number_format($baseFee + $uniqueCode, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} WIB &nbsp;|&nbsp; Hitech School &nbsp;|&nbsp; Dokumen ini digenerate secara otomatis.
    </div>

</body>
</html>
