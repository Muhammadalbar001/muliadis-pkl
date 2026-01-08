<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Valuasi Stok</title>
    <style>
    body {
        font-family: sans-serif;
        font-size: 10px;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
        border-bottom: 3px solid #1e40af;
        padding-bottom: 10px;
    }

    .header h1 {
        margin: 0;
        color: #1e40af;
        text-transform: uppercase;
        font-size: 18px;
    }

    .meta-info {
        width: 100%;
        margin-bottom: 15px;
        font-size: 10px;
        border-collapse: collapse;
    }

    .meta-info td {
        padding: 2px 0;
    }

    .font-bold {
        font-weight: bold;
    }

    table.data {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        font-size: 9px;
    }

    table.data th {
        background-color: #eff6ff;
        color: #1e3a8a;
        font-weight: bold;
        text-transform: uppercase;
        padding: 6px 4px;
        border: 1px solid #bfdbfe;
    }

    table.data td {
        padding: 4px;
        border: 1px solid #bfdbfe;
        vertical-align: middle;
    }

    table.data tr:nth-child(even) {
        background-color: #f8fafc;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .total-row {
        background-color: #1e40af;
        color: white;
        font-weight: bold;
    }

    .footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        font-size: 8px;
        color: #94a3b8;
        text-align: right;
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
        <h1>Laporan Valuasi Aset Stok</h1>
    </div>

    <table class="meta-info">
        <tr>
            <td width="15%" class="font-bold">Cabang</td>
            <td width="35%">: {{ $summary['cabang'] }}</td>
            <td width="15%" class="font-bold">Tanggal Cetak</td>
            <td width="35%">: {{ $tanggal_cetak }}</td>
        </tr>
        <tr>
            <td class="font-bold">Filter Supplier</td>
            <td>: {{ $summary['supplier'] }}</td>
            <td class="font-bold">Dicetak Oleh</td>
            <td>: {{ $user }}</td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="12%">Kode / SKU</th>
                <th width="28%">Nama Produk</th>
                <th width="18%">Supplier</th>
                <th width="8%">Stok</th>
                <th width="12%">HPP (Avg)</th>
                <th width="18%">Total Aset (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $item->sku ?: '-' }}</td>
                <td><span class="font-bold">{{ $item->name_item }}</span></td>
                <td>{{ substr($item->supplier, 0, 20) }}</td>
                <td class="text-center">{{ number_format((float)$item->stok, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format((float)$item->avg, 0, ',', '.') }}</td>
                <td class="text-right font-bold" style="color: #1e40af;">
                    Rp {{ number_format((float)$item->stok * (float)$item->avg, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" class="text-right" style="padding: 8px;">GRAND TOTAL ASET :</td>
                <td class="text-center">{{ number_format($summary['total_qty'], 0, ',', '.') }}</td>
                <td></td>
                <td class="text-right">Rp {{ number_format($summary['total_aset'], 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Halaman <span class="page-number"></span> | Sistem Informasi Manajemen Stok
    </div>
</body>

</html>