<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Kualitas Penjualan (Rasio Retur)</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        color: #1e293b;
        font-size: 11px;
        margin: 0;
        padding: 0;
    }

    .header-container {
        border-bottom: 3px solid #e11d48;
        padding-bottom: 12px;
        margin-bottom: 20px;
    }

    .header-title {
        color: #9f1239;
        font-size: 20px;
        font-weight: bold;
        margin: 0;
        text-transform: uppercase;
    }

    .header-subtitle {
        color: #64748b;
        font-size: 11px;
        margin: 5px 0 0 0;
    }

    .meta-box {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 12px;
        border-radius: 5px;
        margin-bottom: 20px;
        width: 100%;
    }

    .meta-table {
        width: 100%;
    }

    .meta-label {
        font-weight: bold;
        color: #475569;
        width: 120px;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }

    .data-table th {
        background-color: #e11d48;
        color: #ffffff;
        padding: 10px 8px;
        text-transform: uppercase;
        font-size: 10px;
        border: 1px solid #e11d48;
        text-align: center;
    }

    .data-table td {
        padding: 8px;
        border: 1px solid #cbd5e1;
        text-align: right;
    }

    .data-table tr:nth-child(even) {
        background-color: #f8fafc;
    }

    .text-left {
        text-align: left !important;
    }

    .text-center {
        text-align: center !important;
    }

    .font-bold {
        font-weight: bold;
    }

    .signature-section {
        width: 100%;
        margin-top: 40px;
    }

    .signature-box {
        float: right;
        width: 250px;
        text-align: center;
    }

    .signature-space {
        height: 70px;
    }

    .clear {
        clear: both;
    }
    </style>
</head>

<body>
    <div class="header-container">
        <h1 class="header-title">PT Mulia Anugerah Distribusindo</h1>
        <p class="header-subtitle">Executive Information System - Audit Kualitas Transaksi & Kebijakan Retur</p>
    </div>

    <div class="meta-box">
        <table class="meta-table">
            <tr>
                <td class="meta-label">Jenis Laporan</td>
                <td>: Analisa Kualitas Omzet Bersih vs Tingkat Retur Barang</td>
                <td class="meta-label">Dicetak Pada</td>
                <td>: {{ $tgl_cetak }} WITA</td>
            </tr>
            <tr>
                <td class="meta-label">Periode Evaluasi</td>
                <td>: <strong>{{ $periode }}</strong></td>
                <td class="meta-label">Dicetak Oleh</td>
                <td>: {{ $cetak_oleh }}</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th class="text-center" width="10%">Kode</th>
                <th class="text-left" width="25%">Nama Personel Sales</th>
                <th width="15%">Omzet Kotor (Gross)</th>
                <th width="15%">Nilai Retur (Rupiah)</th>
                <th class="text-center" width="10%">Nota Retur</th>
                <th class="text-center" width="10%">Rasio Retur</th>
                <th width="15%" style="background-color: #f0fdf4; color: #15803d;">Omzet Bersih (Net)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center font-bold" style="color: #e11d48;">{{ $row['kode'] }}</td>
                <td class="text-left font-bold">{{ $row['nama'] }}</td>
                <td>Rp {{ number_format($row['gross'], 0, ',', '.') }}</td>
                <td style="color: #b91c1c;">Rp {{ number_format($row['retur'], 0, ',', '.') }}</td>
                <td class="text-center font-bold">{{ $row['qty_retur'] }}</td>
                <td class="text-center font-bold" style="color: {{ $row['rasio'] > 5 ? '#b91c1c' : '#475569' }}">
                    {{ number_format($row['rasio'], 2) }}%</td>
                <td class="font-bold" style="color: #166534; background-color: #f0fdf4;">Rp
                    {{ number_format($row['net'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <p>Banjarmasin, {{ now()->translatedFormat('d F Y') }}</p>
            <p>Mengetahui,</p>
            <div class="signature-space"></div>
            <p class="font-bold" style="text-decoration: underline;">Pimpinan Direksi</p>
            <p>PT Mulia Anugerah Distribusindo</p>
        </div>
        <div class="clear"></div>
    </div>
</body>

</html>