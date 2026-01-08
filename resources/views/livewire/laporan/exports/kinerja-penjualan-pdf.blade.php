<!DOCTYPE html>
<html>

<head>
    <title>Laporan Kinerja Penjualan</title>
    <style>
    body {
        font-family: 'Helvetica', sans-serif;
        font-size: 10px;
        color: #333;
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
        font-size: 16px;
    }

    .header p {
        margin: 2px 0;
        color: #555;
        font-size: 10px;
    }

    /* STYLE BARU UNTUK DESKRIPSI */
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
        background-color: #f1f5f9;
        color: #1e3a8a;
        font-weight: bold;
        text-transform: uppercase;
        padding: 8px 4px;
        border: 1px solid #cbd5e1;
        font-size: 9px;
    }

    td {
        padding: 6px 4px;
        border: 1px solid #cbd5e1;
        vertical-align: middle;
    }

    tr:nth-child(even) {
        background-color: #f8fafc;
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

    .text-green {
        color: #059669;
        font-weight: bold;
    }

    .text-red {
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
        <h1>Laporan Pencapaian Target Sales</h1>

        {{-- DESKRIPSI TAMBAHAN --}}
        <p class="description">
            Laporan ini menyajikan perbandingan antara target penjualan yang ditetapkan dengan realisasi penjualan
            aktual per salesman.
            Data digunakan untuk mengevaluasi kinerja tim sales dalam periode berjalan.
        </p>

        <p style="margin-top: 8px;"><strong>Periode:</strong> {{ $periode }}</p>
        <p>Dicetak Oleh: {{ $cetak_oleh }} | Tanggal: {{ $tgl_cetak }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="8%">Kode</th>
                <th width="20%">Nama Salesman</th>
                <th width="10%">Wilayah</th>
                <th width="15%">Target (Rp)</th>
                <th width="15%">Realisasi (Rp)</th>
                <th width="8%">Achv (%)</th>
                <th width="15%">Gap / Selisih (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $row['kode'] }}</td>
                <td class="font-bold">{{ $row['nama'] }}</td>
                <td class="text-center">{{ $row['cabang'] }}</td>
                <td class="text-right">{{ number_format($row['target_ims'], 0, ',', '.') }}</td>
                <td class="text-right font-bold">{{ number_format($row['real_ims'], 0, ',', '.') }}</td>
                <td class="text-center {{ $row['persen_ims'] >= 100 ? 'text-green' : 'text-red' }}">
                    {{ number_format($row['persen_ims'], 1) }}%
                </td>
                <td class="text-right {{ $row['gap'] >= 0 ? 'text-green' : 'text-red' }}">
                    {{ number_format($row['gap'], 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #1e3a8a; color: white; font-weight: bold;">
                <td colspan="4" class="text-right" style="padding: 8px;">TOTAL KESELURUHAN :</td>
                <td class="text-right">{{ number_format($data->sum('target_ims'), 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($data->sum('real_ims'), 0, ',', '.') }}</td>
                <td class="text-center">
                    @php
                    $totT = $data->sum('target_ims');
                    $totR = $data->sum('real_ims');
                    $totP = $totT > 0 ? ($totR / $totT) * 100 : 0;
                    @endphp
                    {{ number_format($totP, 1) }}%
                </td>
                <td class="text-right">{{ number_format($data->sum('gap'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dokumen Rahasia Perusahaan | Halaman <span class="page-number"></span>
    </div>
</body>

</html>