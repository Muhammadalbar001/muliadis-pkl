<!DOCTYPE html>
<html>

<head>
    <title>Laporan Penjualan Supplier</title>
    <style>
    body {
        font-family: 'Helvetica', sans-serif;
        font-size: 8px;
        /* Font diperkecil sedikit agar muat lebih banyak */
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

    .description {
        text-align: center;
        font-size: 9px;
        color: #64748b;
        margin-top: 5px;
        font-style: italic;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        table-layout: fixed;
        /* Penting agar lebar kolom terkontrol */
    }

    th {
        background-color: #f3e8ff;
        color: #5b21b6;
        font-weight: bold;
        text-transform: uppercase;
        padding: 6px 2px;
        border: 1px solid #ddd6fe;
        font-size: 7px;
        word-wrap: break-word;
        /* Agar nama supplier panjang turun ke bawah */
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

    .text-center {
        text-align: center;
    }

    .font-bold {
        font-weight: bold;
    }

    .sales-col {
        background-color: #f5f3ff;
        font-weight: bold;
        width: 120px;
        /* Lebar tetap untuk kolom salesman */
        text-align: left;
    }

    .total-col {
        background-color: #7c3aed;
        color: white;
        font-weight: bold;
    }

    /* Class untuk memutus halaman */
    .page-break {
        page-break-after: always;
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

    @php
    // LOGIK CHUNKING (PEMECAHAN KOLOM)
    // Kita batasi misal 12 Supplier per halaman agar tabel tidak gepeng
    $maxColsPerPage = 12;
    $supplierChunks = $topSuppliers->chunk($maxColsPerPage);
    $totalPage = $supplierChunks->count();
    @endphp

    @foreach($supplierChunks as $pageIndex => $suppliersInPage)

    {{-- Header hanya muncul di halaman pertama atau setiap halaman (opsional) --}}
    <div class="header">
        <h1>Matriks Penjualan Per Supplier</h1>

        <p class="description">
            Laporan ini memetakan kontribusi penjualan setiap salesman terhadap supplier tertentu.
            (Halaman {{ $pageIndex + 1 }} dari {{ $totalPage }})
        </p>

        <p style="margin-top: 5px;">Periode: {{ $periode }} | Dicetak: {{ $tgl_cetak }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="sales-col">Salesman</th>

                {{-- Loop Supplier untuk Halaman Ini Saja --}}
                @foreach($suppliersInPage as $supp)
                <th>{{ substr($supp, 0, 15) }}</th>
                @endforeach

                {{-- Kolom TOTAL hanya muncul di Halaman Terakhir --}}
                @if($loop->last)
                <th width="80px">TOTAL SALES</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td class="sales-col">{{ $row['nama'] }}</td>

                {{-- Isi Data Supplier --}}
                @foreach($suppliersInPage as $supp)
                <td class="text-right">
                    @php $val = $matrixSupplier[$row['nama']][$supp] ?? 0; @endphp
                    <span style="color: {{ $val > 0 ? '#000' : '#ccc' }}">
                        {{ $val > 0 ? number_format($val, 0, ',', '.') : '-' }}
                    </span>
                </td>
                @endforeach

                {{-- Isi Data Total (Halaman Terakhir) --}}
                @if($loop->last)
                <td class="text-right font-bold" style="background-color: #f3e8ff;">
                    {{ number_format($row['total_supplier_val'], 0, ',', '.') }}
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>

        {{-- Footer Total Per Supplier (Opsional, agar tahu total supplier tsb) --}}
        <tfoot>
            <tr style="background-color: #ddd6fe; font-weight: bold;">
                <td class="sales-col text-center">SUBTOTAL</td>
                @foreach($suppliersInPage as $supp)
                <td class="text-right">
                    @php
                    // Hitung total kolom supplier ini secara manual
                    $colTotal = 0;
                    foreach($data as $d) {
                    $colTotal += ($matrixSupplier[$d['nama']][$supp] ?? 0);
                    }
                    @endphp
                    {{ number_format($colTotal, 0, ',', '.') }}
                </td>
                @endforeach
                @if($loop->last)
                <td class="text-right total-col">
                    {{ number_format($data->sum('total_supplier_val'), 0, ',', '.') }}
                </td>
                @endif
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dokumen Rahasia Perusahaan | Halaman <span class="page-number"></span>
    </div>

    {{-- Tambahkan Page Break kecuali di halaman terakhir --}}
    @if(!$loop->last)
    <div class="page-break"></div>
    @endif

    @endforeach

</body>

</html>