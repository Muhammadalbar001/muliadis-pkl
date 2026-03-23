<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan SPK SAW - Sales</title>
    <style>
    body {
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        color: #1e293b;
        font-size: 11px;
        margin: 0;
        padding: 0;
    }

    /* HEADER STYLING */
    .header-container {
        border-bottom: 3px solid #1e40af;
        padding-bottom: 15px;
        margin-bottom: 25px;
    }

    .header-title {
        color: #1e3a8a;
        font-size: 22px;
        font-weight: bold;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .header-subtitle {
        color: #64748b;
        font-size: 11px;
        margin: 5px 0 0 0;
        letter-spacing: 0.5px;
    }

    /* METADATA BOX */
    .meta-box {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 12px 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .meta-table {
        width: 100%;
    }

    .meta-table td {
        font-size: 11px;
        vertical-align: top;
    }

    .meta-label {
        font-weight: bold;
        color: #475569;
        width: 120px;
    }

    /* DATA TABLE STYLING */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }

    .data-table th {
        background-color: #1e40af;
        color: #ffffff;
        padding: 12px 8px;
        text-align: left;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 1px solid #1e40af;
    }

    .data-table td {
        padding: 10px 8px;
        border: 1px solid #cbd5e1;
        font-size: 11px;
    }

    .data-table tr:nth-child(even) {
        background-color: #f1f5f9;
    }

    /* HIGHLIGHT RANK 1 */
    .rank-1 {
        background-color: #dbeafe !important;
        font-weight: bold;
    }

    .badge-winner {
        color: #1e40af;
        font-size: 10px;
        display: inline-block;
    }

    /* SIGNATURE STYLING */
    .signature-section {
        width: 100%;
        margin-top: 40px;
    }

    .signature-box {
        float: right;
        width: 250px;
        text-align: center;
    }

    .signature-box p {
        margin: 0;
        font-size: 11px;
        color: #334155;
    }

    .signature-space {
        height: 80px;
    }

    .signature-name {
        font-weight: bold;
        text-decoration: underline;
        font-size: 12px;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .clear {
        clear: both;
    }
    </style>
</head>

<body>

    <div class="header-container">
        <h1 class="header-title">PT Mulia Anugerah Distribusindo</h1>
        <p class="header-subtitle">Executive Information System - Smart Analytics Report</p>
    </div>

    <div class="meta-box">
        <table class="meta-table">
            <tr>
                <td class="meta-label">Jenis Laporan</td>
                <td>: Pemeringkatan Kinerja Salesman (Metode SPK SAW)</td>
                <td class="meta-label">Dicetak Pada</td>
                <td>: {{ $tanggal_cetak }} WITA</td>
            </tr>
            <tr>
                <td class="meta-label">Periode Evaluasi</td>
                <td>: <strong>{{ $bulanNama }} {{ $tahun }}</strong></td>
                <td class="meta-label">Dicetak Oleh</td>
                <td>: Pimpinan Eksekutif</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" width="5%">Rank</th>
                <th width="20%">Nama Salesman</th>
                <th width="15%">Area / Cabang</th>
                <th class="text-right" width="15%">Omzet (C1) <br><small>Benefit</small></th>
                <th class="text-center" width="10%">Trans (C2) <br><small>Benefit</small></th>
                <th class="text-right" width="12%">Retur (C3) <br><small>Cost</small></th>
                <th class="text-right" width="13%">Piutang (C4) <br><small>Cost</small></th>
                <th class="text-center" width="10%">Skor Akhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hasil as $index => $row)
            <tr class="{{ $index === 0 ? 'rank-1' : '' }}">
                <td class="text-center">
                    <strong>#{{ $index + 1 }}</strong>
                </td>
                <td>
                    {{ $row['nama'] }}
                    @if($index === 0)
                    <br><span class="badge-winner">★ Top Performer</span>
                    @endif
                </td>
                <td>{{ $row['cabang'] ?? '-' }}</td>
                <td class="text-right">Rp {{ $row['omzet_fmt'] }}</td>
                <td class="text-center">{{ $row['trans_fmt'] }}</td>
                <td class="text-right">Rp {{ $row['retur_fmt'] }}</td>
                <td class="text-right">Rp {{ $row['piutang_fmt'] }}</td>
                <td class="text-center">
                    <strong style="color: {{ $index === 0 ? '#1e40af' : '#334155' }}; font-size: 13px;">
                        {{ number_format($row['skor_akhir'], 3) }}
                    </strong>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center" style="padding: 20px;">Data evaluasi tidak tersedia untuk periode
                    ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <p>Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Mengetahui,</p>
            <div class="signature-space"></div>
            <p class="signature-name">Pimpinan Direksi</p>
            <p>PT Mulia Anugerah Distribusindo</p>
        </div>
        <div class="clear"></div>
    </div>

</body>

</html>