<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Hybrid AHP-SAW - Sales</title>
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

    /* METADATA & AI BOX */
    .top-panels {
        width: 100%;
        margin-bottom: 20px;
    }

    .meta-box,
    .ai-box {
        border-radius: 5px;
        padding: 12px 15px;
        vertical-align: top;
    }

    .meta-box {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        width: 55%;
        margin-right: 2%;
    }

    .ai-box {
        background-color: #eff6ff;
        border: 1px solid #bfdbfe;
        width: 40%;
    }

    .meta-table {
        width: 100%;
    }

    .meta-table td {
        font-size: 11px;
        vertical-align: top;
        padding: 3px 0;
    }

    .meta-label {
        font-weight: bold;
        color: #475569;
        width: 120px;
    }

    .ai-title {
        font-weight: bold;
        color: #1d4ed8;
        font-size: 10px;
        text-transform: uppercase;
        border-bottom: 1px solid #93c5fd;
        padding-bottom: 5px;
        margin-top: 0;
        margin-bottom: 5px;
    }

    .ai-table {
        width: 100%;
        font-size: 11px;
    }

    .ai-table td {
        padding: 3px 0;
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
        margin-top: 3px;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
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

    .clear {
        clear: both;
    }
    </style>
</head>

<body>

    <div class="header-container">
        <h1 class="header-title">PT Mulia Anugerah Distribusindo</h1>
        <p class="header-subtitle">Executive Information System - Smart Analytics Report (Hybrid AI)</p>
    </div>

    <table class="top-panels">
        <tr>
            <td class="meta-box">
                <table class="meta-table">
                    <tr>
                        <td class="meta-label">Jenis Laporan</td>
                        <td>: Kinerja Salesman (Hybrid AHP-SAW)</td>
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

            {{-- KOTAK METRIK EVALUASI AI (PENTING UNTUK DOSEN) --}}
            <td class="ai-box">
                <h4 class="ai-title">Validasi Keputusan Matematis</h4>
                <table class="ai-table">
                    <tr>
                        <td><strong>AHP Consistency Ratio (CR)</strong></td>
                        <td class="text-right">
                            <strong>{{ isset($ahpCR) ? number_format($ahpCR, 3) : '0' }}</strong>
                            <span style="font-size: 9px; color: #047857;">(Valid < 0.1)</span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>RMSE (Tingkat Error)</strong></td>
                        <td class="text-right">
                            <strong>{{ isset($nilaiRMSE) ? number_format($nilaiRMSE, 3) : '0' }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-size: 9px; color: #1d4ed8; margin-top: 5px; display: block;">
                            *RMSE dihitung dengan membandingkan error antara Sistem Cerdas usulan terhadap Sistem
                            Konvensional lama.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" width="5%">Rank</th>
                <th width="20%">Nama Salesman</th>
                <th class="text-right" width="13%">Omzet (C1)<br><small>Benefit</small></th>
                <th class="text-right" width="13%">Retur (C3)<br><small>Cost</small></th>
                <th class="text-right" width="13%">Piutang (C4)<br><small>Cost</small></th>
                <th class="text-center" width="12%" style="background-color: #64748b;">Skor Lama<br><small>Sistem
                        Manual</small></th>
                <th class="text-center" width="14%" style="background-color: #4338ca;">Skor Akhir<br><small>AHP-SAW
                        (V)</small></th>
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
                <td class="text-right">Rp {{ $row['omzet_fmt'] }}</td>
                <td class="text-right">Rp {{ $row['retur_fmt'] }}</td>
                <td class="text-right">Rp {{ $row['piutang_fmt'] }}</td>

                {{-- Skor Konvensional Lama (Tercoret agar dramatis) --}}
                <td class="text-center" style="color: #94a3b8; text-decoration: line-through;">
                    {{ number_format($row['skor_manual'] ?? 0, 3) }}
                </td>

                {{-- Skor AHP-SAW AI --}}
                <td class="text-center">
                    <strong style="color: {{ $index === 0 ? '#1e40af' : '#334155' }}; font-size: 13px;">
                        {{ number_format($row['skor_akhir'], 3) }}
                    </strong>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 20px;">Data evaluasi AI tidak tersedia untuk periode
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