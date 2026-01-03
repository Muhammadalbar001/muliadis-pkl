<!DOCTYPE html>
<html>

<head>
    <title>Laporan Penjualan Supplier</title>
    <style>
    body {
        font-family: 'Helvetica', sans-serif;
        font-size: 9px;
        color: #333;
    }

    .header {
        text-align: center;
        margin-bottom: 15px;
        border-bottom: 3px solid #7c3aed;
        padding-bottom: 10px;
    }

    .header h1 {
        margin: 0;
        color: #7c3aed;
        text-transform: uppercase;
        font-size: 16px;
    }

    .header p {
        margin: 2px 0;
        color: #555;
        font-size: 9px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th {
        background-color: #f3e8ff;
        color: #5b21b6;
        font-weight: bold;
        text-transform: uppercase;
        padding: 6px 2px;
        border: 1px solid #ddd6fe;
        font-size: 8px;
    }

    td {
        padding: 4px 2px;
        border: 1px solid #ddd6fe;
        vertical-align: middle;
    }

    tr:nth-child(even) {
        background-color: #faf5ff;
    }

    .text-right {
        text-align: right;
    }

    .font-bold {
        font-weight: bold;
    }

    .sales-col {
        background-color: #f5f3ff;
        font-weight: bold;
        width: 15%;
    }

    .footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        font-size: 8px;
        color: #94a3b8;
        text-align: right;
    }
    </style>
</head>

<body>
    <div class="header">
        <h1>Matriks Penjualan Per Supplier</h1>
        <p>Periode: {{ $periode }} | Dicetak: {{ $tgl_cetak }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="sales-col">Salesman</th>
                @foreach($topSuppliers as $supp)
                <th>{{ substr($supp, 0, 10) }}</th>
                @endforeach
                <th>TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td class="sales-col">{{ $row['nama'] }}</td>
                @foreach($topSuppliers as $supp)
                <td class="text-right">
                    @php $val = $matrixSupplier[$row['nama']][$supp] ?? 0; @endphp
                    <span style="color: {{ $val > 0 ? '#000' : '#ccc' }}">
                        {{ $val > 0 ? number_format($val, 0, ',', '.') : '-' }}
                    </span>
                </td>
                @endforeach
                <td class="text-right font-bold">{{ number_format($row['total_supplier_val'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Halaman <span class="page-number"></span>
    </div>
</body>

</html>