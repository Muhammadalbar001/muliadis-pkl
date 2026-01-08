<!DOCTYPE html>
<html>

<head>
    <title>Laporan Produktivitas</title>
    <style>
    body {
        font-family: 'Helvetica', sans-serif;
        font-size: 10px;
        color: #333;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
        border-bottom: 3px solid #0284c7;
        padding-bottom: 10px;
    }

    .header h1 {
        margin: 0;
        color: #0284c7;
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
        background-color: #e0f2fe;
        color: #075985;
        font-weight: bold;
        text-transform: uppercase;
        padding: 8px 4px;
        border: 1px solid #bae6fd;
    }

    td {
        padding: 6px 4px;
        border: 1px solid #bae6fd;
        vertical-align: middle;
        text-align: center;
    }

    tr:nth-child(even) {
        background-color: #f0f9ff;
    }

    .text-left {
        text-align: left;
    }

    .font-bold {
        font-weight: bold;
    }

    .ratio-good {
        color: #059669;
        font-weight: bold;
    }

    .ratio-bad {
        color: #64748b;
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
        <h1>Laporan Produktivitas & Efektivitas Kunjungan</h1>

        {{-- DESKRIPSI TAMBAHAN --}}
        <p class="description">
            Laporan ini mengukur produktivitas sales berdasarkan jumlah toko yang dikunjungi (OA - Outlet Active)
            dibandingkan dengan jumlah nota penjualan yang berhasil dicetak (EC - Effective Call) dengan nilai minimal
            tertentu.
        </p>

        <p style="margin-top: 8px;">
            <strong>Periode:</strong> {{ $periode }} |
            <strong>Min. Nota Efektif:</strong> Rp {{ number_format($minNominal, 0, ',', '.') }}
        </p>
        <p>Dicetak Oleh: {{ $cetak_oleh }} | Tanggal: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Kode</th>
                <th width="30%" class="text-left">Nama Salesman</th>
                <th width="15%">Wilayah</th>
                <th width="15%">Outlet Aktif (OA)</th>
                <th width="15%">Nota Efektif (EC)</th>
                <th width="10%">Rasio EC/OA</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row['kode'] }}</td>
                <td class="text-left font-bold">{{ $row['nama'] }}</td>
                <td>{{ $row['cabang'] }}</td>
                <td style="font-size: 11px;">{{ $row['real_oa'] }}</td>
                <td style="font-size: 11px; font-weight: bold;">{{ $row['ec'] }}</td>
                <td>
                    @php $ratio = $row['real_oa'] > 0 ? ($row['ec'] / $row['real_oa']) * 100 : 0; @endphp
                    <span class="{{ $ratio > 50 ? 'ratio-good' : 'ratio-bad' }}">
                        {{ number_format($ratio, 1) }}%
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #0369a1; color: white; font-weight: bold;">
                <td colspan="4" class="text-right" style="padding: 8px;">TOTAL NASIONAL :</td>
                <td>{{ number_format($data->sum('real_oa'), 0, ',', '.') }}</td>
                <td>{{ number_format($data->sum('ec'), 0, ',', '.') }}</td>
                <td>
                    @php
                    $tOA = $data->sum('real_oa');
                    $tEC = $data->sum('ec');
                    $tRatio = $tOA > 0 ? ($tEC / $tOA) * 100 : 0;
                    @endphp
                    {{ number_format($tRatio, 1) }}%
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dokumen Rahasia Perusahaan | Halaman <span class="page-number"></span>
    </div>
</body>

</html>