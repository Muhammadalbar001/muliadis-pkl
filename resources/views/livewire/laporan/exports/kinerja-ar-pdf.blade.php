<!DOCTYPE html>
<html>

<head>
    <title>Laporan Monitoring Kredit</title>
    <style>
    body {
        font-family: 'Helvetica', sans-serif;
        font-size: 10px;
        color: #333;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
        border-bottom: 3px solid #ea580c;
        padding-bottom: 10px;
    }

    .header h1 {
        margin: 0;
        color: #ea580c;
        text-transform: uppercase;
        font-size: 16px;
    }

    .header p {
        margin: 2px 0;
        color: #555;
        font-size: 10px;
    }

    /* DESKRIPSI TAMBAHAN */
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
    }

    th {
        background-color: #fff7ed;
        color: #9a3412;
        font-weight: bold;
        text-transform: uppercase;
        padding: 8px 4px;
        border: 1px solid #fed7aa;
        font-size: 9px;
    }

    td {
        padding: 6px 4px;
        border: 1px solid #fed7aa;
        vertical-align: middle;
    }

    tr:nth-child(even) {
        background-color: #ffedd5;
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

    .text-danger {
        color: #dc2626;
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
        <h1>Laporan Monitoring Piutang & Kredit</h1>

        {{-- DESKRIPSI TAMBAHAN --}}
        <p class="description">
            Laporan ini memantau status piutang dagang (AR) yang beredar di pelanggan. Data ini mencakup total piutang,
            piutang lancar, serta piutang macet (>30 hari) untuk mengukur risiko kredit per salesman.
        </p>

        <p style="margin-top: 8px;"><strong>Periode Data:</strong> {{ $periode }}</p>
        <p>Dicetak Oleh: {{ $cetak_oleh }} | Tanggal: {{ $tgl_cetak }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="10%">Kode Sales</th>
                <th width="25%">Nama Salesman</th>
                <th width="12%">Wilayah</th>
                <th width="15%">Total Piutang</th>
                <th width="15%">Piutang Lancar</th>
                <th width="15%">Macet (>30 Hari)</th>
                <th width="5%">Rasio Macet</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $row['kode'] }}</td>
                <td class="font-bold">{{ $row['nama'] }}</td>
                <td class="text-center">{{ $row['cabang'] }}</td>
                <td class="text-right font-bold">{{ number_format($row['ar_total'], 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($row['ar_lancar'], 0, ',', '.') }}</td>
                <td class="text-right text-danger">{{ number_format($row['ar_macet'], 0, ',', '.') }}</td>
                <td class="text-center {{ $row['ar_persen_macet'] > 10 ? 'text-danger' : '' }}">
                    {{ number_format($row['ar_persen_macet'], 1) }}%
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #c2410c; color: white; font-weight: bold;">
                <td colspan="4" class="text-right" style="padding: 8px;">GRAND TOTAL :</td>
                <td class="text-right">{{ number_format($data->sum('ar_total'), 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($data->sum('ar_lancar'), 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($data->sum('ar_macet'), 0, ',', '.') }}</td>
                <td class="text-center">
                    @php
                    $tAR = $data->sum('ar_total');
                    $tMacet = $data->sum('ar_macet');
                    $tRasio = $tAR > 0 ? ($tMacet / $tAR) * 100 : 0;
                    @endphp
                    {{ number_format($tRasio, 1) }}%
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dokumen Rahasia Perusahaan | Halaman <span class="page-number"></span>
    </div>
</body>

</html>