<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Laporan Analisa Margin - {{ $cabang }}</title>
    <style>
    /* Setup Halaman & Font */
    @page {
        margin: 20px 30px;
    }

    body {
        font-family: 'Helvetica', 'Arial', sans-serif;
        font-size: 10px;
        color: #333;
        line-height: 1.3;
    }

    /* Header Laporan (Kop) */
    .header {
        width: 100%;
        border-bottom: 3px solid #1e40af;
        /* Garis biru tebal */
        padding-bottom: 10px;
        margin-bottom: 20px;
        text-align: center;
        /* Center align header content */
    }

    .header-title {
        font-size: 18px;
        font-weight: 800;
        text-transform: uppercase;
        color: #1e40af;
        margin: 0;
    }

    .header-subtitle {
        font-size: 12px;
        font-weight: bold;
        color: #555;
        margin: 2px 0 0;
        text-transform: uppercase;
    }

    /* DESKRIPSI TAMBAHAN */
    .description {
        text-align: center;
        font-size: 9px;
        color: #64748b;
        margin-top: 5px;
        font-style: italic;
        margin-bottom: 5px;
    }

    /* Informasi Metadata Laporan */
    .meta-info {
        width: 100%;
        margin-bottom: 15px;
    }

    .meta-table td {
        padding: 2px 0;
        vertical-align: top;
    }

    .label {
        font-weight: bold;
        color: #555;
        width: 100px;
    }

    /* Tabel Utama */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9px;
    }

    .data-table th {
        background-color: #f1f5f9;
        /* Abu-abu muda */
        color: #1e293b;
        /* Teks gelap */
        font-weight: 800;
        text-transform: uppercase;
        padding: 8px 5px;
        border: 1px solid #cbd5e1;
        text-align: center;
    }

    .data-table td {
        padding: 6px 5px;
        border: 1px solid #cbd5e1;
        vertical-align: middle;
    }

    /* Zebra Striping (Baris selang-seling) */
    .data-table tr:nth-child(even) {
        background-color: #f8fafc;
    }

    /* Utility Classes */
    .text-left {
        text-align: left;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .font-bold {
        font-weight: bold;
    }

    .text-red {
        color: #dc2626;
        font-weight: bold;
    }

    .text-green {
        color: #059669;
        font-weight: bold;
    }

    .truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
        display: inline-block;
    }

    /* Footer */
    .footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        font-size: 8px;
        color: #94a3b8;
        border-top: 1px solid #e2e8f0;
        padding-top: 5px;
    }

    .page-number:after {
        content: counter(page);
    }
    </style>
</head>

<body>

    <div class="header">
        <h1 class="header-title">Laporan Analisa Margin & Profitabilitas</h1>

        {{-- DESKRIPSI TAMBAHAN --}}
        <p class="description">
            Laporan ini menganalisa selisih (margin) antara Harga Jual Final dengan Harga Pokok Pembelian (HPP) yang
            telah memperhitungkan PPN Masukan.
            Margin negatif menandakan harga jual di bawah modal.
        </p>

        <p class="header-subtitle">Cabang: {{ $cabang }}</p>
    </div>

    <div class="meta-info">
        <table class="meta-table" style="width: 100%">
            <tr>
                <td class="label">Supplier</td>
                <td>: {{ Str::limit($suppliers, 100) }}</td>
                <td class="label" style="text-align: right;">Dicetak Oleh</td>
                <td style="width: 150px; text-align: right;">: {{ $user_pencetak }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Cetak</td>
                <td>: {{ $tanggal_cetak }}</td>
                <td class="label" style="text-align: right;">Total Item</td>
                <td style="text-align: right;">: {{ count($products) }} Produk</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="15%">Supplier</th>
                <th width="27%">Nama Produk / SKU</th>
                <th width="5%">Stok</th>
                <th width="12%">HPP Final<br><span style="font-size: 7px; font-weight: normal">(Modal + PPN)</span></th>
                <th width="12%">Harga Jual</th>
                <th width="12%">Margin (Rp)</th>
                <th width="8%">Margin (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $index => $p)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <span class="font-bold">{{ $p['last_supplier'] }}</span>
                </td>
                <td>
                    <div class="font-bold">{{ $p['name_item'] }}</div>
                    @if(!empty($p['sku']))
                    <div style="font-size: 8px; color: #64748b;">SKU: {{ $p['sku'] }}</div>
                    @endif
                </td>
                <td class="text-center">{{ $p['stock'] }}</td>
                <td class="text-right">
                    Rp {{ number_format($p['avg_ppn'], 0, ',', '.') }}
                </td>
                <td class="text-right">
                    Rp {{ number_format($p['harga_jual'], 0, ',', '.') }}
                </td>
                <td class="text-right {{ $p['margin_rp'] < 0 ? 'text-red' : 'text-green' }}">
                    Rp {{ number_format($p['margin_rp'], 0, ',', '.') }}
                </td>
                <td class="text-center {{ $p['margin_persen'] < 0 ? 'text-red' : 'text-green' }}">
                    {{ number_format($p['margin_persen'], 2, ',', '.') }}%
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <table width="100%">
            <tr>
                <td width="50%">Sistem Informasi Manajemen - Dicetak Otomatis</td>
                <td width="50%" style="text-align: right;">Halaman <span class="page-number"></span></td>
            </tr>
        </table>
    </div>

</body>

</html>