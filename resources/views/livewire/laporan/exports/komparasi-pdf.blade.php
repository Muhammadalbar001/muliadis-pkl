<!DOCTYPE html>
<html>

<head>
    <title>{{ $judul }}</title>
    <style>
    body {
        font-family: sans-serif;
        font-size: 10px;
        color: #333;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
        border-bottom: 3px solid #475569;
        padding-bottom: 10px;
    }

    .header h1 {
        margin: 0;
        text-transform: uppercase;
        font-size: 16px;
        color: #1e293b;
    }

    .meta {
        font-size: 9px;
        color: #64748b;
        margin-top: 5px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    th {
        background-color: #f1f5f9;
        padding: 8px;
        border: 1px solid #cbd5e1;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 9px;
    }

    td {
        padding: 5px 8px;
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

    /* Warna Indikator */
    .text-blue {
        color: #2563eb;
    }

    .text-red {
        color: #dc2626;
    }

    .text-green {
        color: #16a34a;
    }

    .summary-box {
        margin-top: 20px;
        padding: 15px;
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 5px;
    }

    .footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        font-size: 8px;
        text-align: right;
        color: #94a3b8;
        border-top: 1px solid #e2e8f0;
        padding-top: 5px;
    }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $judul }}</h1>
        <div class="meta">
            Periode: <strong>{{ $periode }}</strong> | Cabang: <strong>{{ $cabang }}</strong> <br>
            Dicetak Oleh: {{ $cetak_oleh }} | {{ $tgl_cetak }}
        </div>
    </div>

    {{-- RINGKASAN ATAS --}}
    <table style="width: 100%; margin-bottom: 20px; border: none;">
        <tr style="background-color: white;">
            <td style="border: 1px solid #e2e8f0; padding: 10px; width: 33%; text-align: center;">
                <span style="font-size: 9px; color: #64748b; display: block; margin-bottom: 5px;">TOTAL
                    {{ strtoupper($summary['label_A']) }}</span>
                <span style="font-size: 14px; font-weight: bold; color: #2563eb;">Rp
                    {{ number_format($summary['total_A'], 0, ',', '.') }}</span>
            </td>
            <td style="border: 1px solid #e2e8f0; padding: 10px; width: 33%; text-align: center;">
                <span style="font-size: 9px; color: #64748b; display: block; margin-bottom: 5px;">TOTAL
                    {{ strtoupper($summary['label_B']) }}</span>
                <span style="font-size: 14px; font-weight: bold; color: #dc2626;">Rp
                    {{ number_format($summary['total_B'], 0, ',', '.') }}</span>
            </td>
            <td style="border: 1px solid #e2e8f0; padding: 10px; width: 33%; text-align: center;">
                <span style="font-size: 9px; color: #64748b; display: block; margin-bottom: 5px;">
                    {{ $jenis == 'omzet' ? 'RASIO RETUR' : 'RASIO COLLECTION' }}
                </span>
                @php
                $ratioGlobal = $summary['total_A'] > 0 ? ($summary['total_B'] / $summary['total_A']) * 100 : 0;
                $isGood = $jenis == 'omzet' ? ($ratioGlobal < 5) : ($ratioGlobal> 80); // Contoh threshold
                    $color = $isGood ? '#16a34a' : '#dc2626';
                    @endphp
                    <span style="font-size: 14px; font-weight: bold; color: {{ $color }};">
                        {{ number_format($ratioGlobal, 2) }}%
                    </span>
            </td>
        </tr>
    </table>

    {{-- TABEL HARIAN --}}
    <table>
        <thead>
            <tr>
                <th width="10%">Tanggal</th>
                <th width="30%">{{ $summary['label_A'] }}</th>
                <th width="30%">{{ $summary['label_B'] }}</th>
                <th width="20%">Selisih (Gap)</th>
                <th width="10%">Rasio (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td class="text-center">{{ \Carbon\Carbon::parse($row['full_date'])->format('d/m/Y') }}</td>
                <td class="text-right">{{ number_format($row['val_A'], 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($row['val_B'], 0, ',', '.') }}</td>

                {{-- GAP Logic --}}
                @php $gapColor = ($jenis == 'omzet') ? 'text-black' : ($row['gap'] > 0 ? 'text-red' : 'text-green');
                @endphp
                <td class="text-right {{ $gapColor }}">
                    {{ number_format($row['gap'], 0, ',', '.') }}
                </td>

                {{-- Rasio Logic --}}
                @php
                if($jenis == 'omzet') {
                // Retur: Makin kecil makin hijau
                $ratioClass = $row['persen'] > 5 ? 'text-red' : 'text-green';
                } else {
                // Lunas: Makin besar makin hijau (diatas 80% bagus misal)
                $ratioClass = $row['persen'] < 50 ? 'text-red' : 'text-green' ; } @endphp <td
                    class="text-center font-bold {{ $ratioClass }}">
                    {{ number_format($row['persen'], 1) }}%
                    </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak otomatis oleh sistem
    </div>
</body>

</html>