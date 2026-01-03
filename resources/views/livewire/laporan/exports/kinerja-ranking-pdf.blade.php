<!DOCTYPE html>
<html>

<head>
    <title>Ranking Sales Performance</title>
    <style>
    body {
        font-family: sans-serif;
        color: #333;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
        border-bottom: 2px solid #059669;
        padding-bottom: 10px;
    }

    .header h1 {
        margin: 0;
        color: #059669;
        text-transform: uppercase;
        font-size: 18px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px;
        margin-top: 10px;
    }

    .table th {
        background-color: #059669;
        color: white;
        padding: 8px;
        text-transform: uppercase;
    }

    .table td {
        padding: 6px 8px;
        border-bottom: 1px solid #eee;
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

    .rank-1 {
        background-color: #fef9c3;
    }

    /* Gold Background for #1 */
    .badge {
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 9px;
        color: white;
        font-weight: bold;
    }

    .bg-green {
        background-color: #059669;
    }

    .bg-red {
        background-color: #dc2626;
    }
    </style>
</head>

<body>
    <div class="header">
        <h1>Leaderboard Kinerja Sales</h1>
        <p style="margin: 5px 0; font-size: 12px; color: #666;">Periode: {{ $periode }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="5%">Rank</th>
                <th width="35%">Nama Salesman</th>
                <th width="20%">Target</th>
                <th width="20%">Realisasi</th>
                <th width="10%">Gap</th>
                <th width="10%">Achv (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
            <tr class="{{ $index == 0 ? 'rank-1' : '' }}">
                <td class="text-center font-bold">{{ $index + 1 }}</td>
                <td>
                    <span class="font-bold">{{ strtoupper($item['nama']) }}</span><br>
                    <span style="font-size: 9px; color: #666;">{{ $item['cabang'] }}</span>
                </td>
                <td class="text-right">Rp {{ number_format($item['target'], 0, ',', '.') }}</td>
                <td class="text-right font-bold">Rp {{ number_format($item['realisasi'], 0, ',', '.') }}</td>
                <td class="text-right" style="color: {{ $item['gap'] >= 0 ? 'green' : 'red' }}">
                    {{ number_format($item['gap'], 0, ',', '.') }}
                </td>
                <td class="text-center">
                    <span class="badge {{ $item['persen'] >= 100 ? 'bg-green' : 'bg-red' }}">
                        {{ number_format($item['persen'], 1) }}%
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>