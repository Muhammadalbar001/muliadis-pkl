<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Segmentasi RFM - Pelanggan</title>
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
        border-bottom: 3px solid #c026d3;
        /* Warna Fuchsia */
        padding-bottom: 15px;
        margin-bottom: 20px;
    }

    .header-title {
        color: #a21caf;
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

    /* METADATA & SUMMARY BOX */
    .top-panels {
        width: 100%;
        margin-bottom: 20px;
    }

    .meta-box,
    .summary-box {
        background-color: #fdf4ff;
        border: 1px solid #f0abfc;
        padding: 10px 15px;
        border-radius: 5px;
        vertical-align: top;
    }

    .meta-box {
        width: 55%;
        margin-right: 2%;
    }

    .summary-box {
        width: 40%;
    }

    .meta-table {
        width: 100%;
    }

    .meta-table td {
        font-size: 11px;
        padding: 3px 0;
    }

    .meta-label {
        font-weight: bold;
        color: #4a044e;
        width: 100px;
    }

    .summary-title {
        font-weight: bold;
        color: #86198f;
        font-size: 10px;
        text-transform: uppercase;
        border-bottom: 1px solid #f0abfc;
        padding-bottom: 5px;
        margin-top: 0;
        margin-bottom: 5px;
    }

    .summary-table {
        width: 100%;
        font-size: 10px;
    }

    .summary-table td {
        padding: 2px 0;
    }

    /* DATA TABLE STYLING */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }

    .data-table th {
        background-color: #a21caf;
        color: #ffffff;
        padding: 10px 8px;
        text-align: center;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 1px solid #a21caf;
    }

    .data-table td {
        padding: 8px;
        border: 1px solid #cbd5e1;
        font-size: 11px;
        vertical-align: middle;
    }

    .data-table tr:nth-child(even) {
        background-color: #f8fafc;
    }

    .text-left {
        text-align: left !important;
    }

    .text-right {
        text-align: right !important;
    }

    .text-center {
        text-align: center !important;
    }

    .font-bold {
        font-weight: bold;
    }

    /* BADGES FOR SEGMENTS */
    .badge {
        display: inline-block;
        padding: 3px 8px;
        font-size: 9px;
        font-weight: bold;
        border-radius: 3px;
        text-transform: uppercase;
        text-align: center;
    }

    .badge-utama {
        background-color: #d1fae5;
        color: #047857;
        border: 1px solid #34d399;
    }

    .badge-setia {
        background-color: #dbeafe;
        color: #1d4ed8;
        border: 1px solid #60a5fa;
    }

    .badge-potensial {
        background-color: #cffafe;
        color: #0e7490;
        border: 1px solid #22d3ee;
    }

    .badge-berisiko {
        background-color: #ffedd5;
        color: #c2410c;
        border: 1px solid #fb923c;
    }

    .badge-pasif {
        background-color: #ffe4e6;
        color: #e11d48;
        border: 1px solid #fb7185;
    }

    .badge-reguler {
        background-color: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
    }

    /* SIGNATURE */
    .signature-section {
        width: 100%;
        margin-top: 30px;
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

    .clear {
        clear: both;
    }
    </style>
</head>

<body>

    <div class="header-container">
        <h1 class="header-title">PT Mulia Anugerah Distribusindo</h1>
        <p class="header-subtitle">Executive Information System - Laporan Segmentasi RFM</p>
    </div>

    <table class="top-panels">
        <tr>
            <td class="meta-box">
                <table class="meta-table">
                    <tr>
                        <td class="meta-label">Jenis Laporan</td>
                        <td>: Analisis Perilaku Pelanggan (Metode RFM)</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Periode Evaluasi</td>
                        <td>: <strong>{{ $bulanNama }} {{ $tahun }}</strong></td>
                    </tr>
                    <tr>
                        <td class="meta-label">Dicetak Pada</td>
                        <td>: {{ $tanggal_cetak }} WITA</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Dicetak Oleh</td>
                        <td>: Pimpinan Eksekutif</td>
                    </tr>
                </table>
            </td>
            <td style="width: 5%;"></td>
            <td class="summary-box">
                <h4 class="summary-title">Ringkasan Segmen Pelanggan</h4>
                <table class="summary-table">
                    <tr>
                        <td>Utama: <strong>{{ $summary['Pelanggan Utama'] ?? 0 }}</strong></td>
                        <td>Setia: <strong>{{ $summary['Pelanggan Setia'] ?? 0 }}</strong></td>
                        <td>Potensial: <strong>{{ $summary['Pelanggan Potensial'] ?? 0 }}</strong></td>
                    </tr>
                    <tr>
                        <td>Reguler: <strong>{{ $summary['Pelanggan Reguler'] ?? 0 }}</strong></td>
                        <td>Berisiko: <strong>{{ $summary['Berisiko Pindah'] ?? 0 }}</strong></td>
                        <td>Pasif: <strong>{{ $summary['Pelanggan Pasif'] ?? 0 }}</strong></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th class="text-left" width="25%">Nama Pelanggan</th>
                <th width="12%">Keterbaruan (R)<br><small>Hari / Skor</small></th>
                <th width="12%">Frekuensi (F)<br><small>Nota / Skor</small></th>
                <th width="18%">Moneter (M)<br><small>Nominal / Skor</small></th>
                <th width="10%">Skor RFM</th>
                <th width="18%">Klasifikasi Segmen</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hasil as $index => $row)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-left font-bold">{{ $row['nama'] }}</td>
                <td class="text-center">
                    {{ number_format($row['r_raw'], 0, ',', '.') }} Hari<br>
                    <span style="color: #64748b; font-size: 9px;">Skor: {{ $row['r_score'] }}</span>
                </td>
                <td class="text-center">
                    {{ number_format($row['f_raw'], 0, ',', '.') }} Nota<br>
                    <span style="color: #64748b; font-size: 9px;">Skor: {{ $row['f_score'] }}</span>
                </td>
                <td class="text-right">
                    Rp {{ $row['m_fmt'] }}<br>
                    <span style="color: #64748b; font-size: 9px; text-align: right; display: block;">Skor:
                        {{ $row['m_score'] }}</span>
                </td>
                <td class="text-center">
                    <strong style="color: #a21caf; font-size: 13px;">{{ $row['rfm_concat'] }}</strong>
                </td>
                <td class="text-center">
                    @php
                    $badgeClass = 'badge-reguler';
                    if($row['segment'] == 'Pelanggan Utama') $badgeClass = 'badge-utama';
                    elseif($row['segment'] == 'Pelanggan Setia') $badgeClass = 'badge-setia';
                    elseif($row['segment'] == 'Pelanggan Potensial') $badgeClass = 'badge-potensial';
                    elseif($row['segment'] == 'Berisiko Pindah') $badgeClass = 'badge-berisiko';
                    elseif($row['segment'] == 'Pelanggan Pasif') $badgeClass = 'badge-pasif';
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $row['segment'] }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 20px;">Data evaluasi RFM tidak tersedia untuk
                    periode ini.</td>
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