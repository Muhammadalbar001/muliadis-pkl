<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Kinerja Segmentasi Pelanggan</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        color: #1e293b;
        font-size: 11px;
        margin: 0;
        padding: 0;
    }

    .header-container {
        border-bottom: 3px solid #4f46e5;
        padding-bottom: 12px;
        margin-bottom: 20px;
    }

    .header-title {
        color: #1e3a8a;
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
        background-color: #4f46e5;
        color: #ffffff;
        padding: 10px 8px;
        text-transform: uppercase;
        font-size: 10px;
        border: 1px solid #4f46e5;
        text-align: center;
    }

    .data-table td {
        padding: 8px;
        border: 1px solid #cbd5e1;
        text-align: center;
    }

    .data-table tr:nth-child(even) {
        background-color: #f8fafc;
    }

    .text-left {
        text-align: left !important;
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
        <p class="header-subtitle">Executive Information System - Rapor Klasifikasi Distribusi Pelanggan per Sales</p>
    </div>

    <div class="meta-box">
        <table class="meta-table">
            <tr>
                <td class="meta-label">Jenis Laporan</td>
                <td>: Analisa Sebaran Kinerja Segmentasi Toko Aktif</td>
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
                <th width="5%">No</th>
                <th width="15%">Kode Sales</th>
                <th class="text-left" width="30%">Nama Personel Salesman</th>
                <th width="15%" style="background-color: #ecfdf5; color: #065f46;">Toko VIP (Utama)</th>
                <th width="15%" style="background-color: #eff6ff; color: #1e40af;">Toko Menengah</th>
                <th width="15%" style="background-color: #fff1f2; color: #9f1239;">Toko Pasif (Churn)</th>
                <th width="10%">Total Toko</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="font-bold" style="color: #4f46e5;">{{ $row['kode'] }}</td>
                <td class="text-left font-bold">{{ $row['nama'] }}</td>
                <td class="font-bold" style="color: #047857; font-size: 12px;">{{ $row['vip'] }}</td>
                <td class="font-bold" style="color: #1d4ed8; font-size: 12px;">{{ $row['menengah'] }}</td>
                <td class="font-bold" style="color: #b91c1c; font-size: 12px;">{{ $row['pasif'] }}</td>
                <td class="font-bold" style="background-color: #f1f5f9;">{{ $row['total'] }}</td>
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