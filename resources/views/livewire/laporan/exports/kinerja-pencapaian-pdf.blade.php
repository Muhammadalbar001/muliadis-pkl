<!DOCTYPE html>
<html>

<head>
    <title>Rapor Kinerja Sales</title>
    <style>
    body {
        font-family: sans-serif;
        color: #333;
        font-size: 10px;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
        border-bottom: 2px solid #2563eb;
        padding-bottom: 10px;
    }

    .header h1 {
        margin: 0;
        color: #2563eb;
        text-transform: uppercase;
        font-size: 18px;
    }

    /* Grid Layout Simulasi dengan Float */
    .card-container {
        width: 100%;
        overflow: hidden;
    }

    .card {
        width: 31%;
        /* 3 Kolom */
        float: left;
        margin-right: 2%;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 10px;
        box-sizing: border-box;
        background-color: #fff;
    }

    /* Hapus margin kanan setiap kartu ke-3 agar rapi */
    .card:nth-child(3n) {
        margin-right: 0;
    }

    .card-header {
        border-bottom: 1px solid #eee;
        padding-bottom: 5px;
        margin-bottom: 5px;
    }

    .sales-name {
        font-size: 11px;
        font-weight: bold;
        text-transform: uppercase;
        color: #1e293b;
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .sales-info {
        font-size: 9px;
        color: #64748b;
    }

    .row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 3px;
    }

    .label {
        color: #64748b;
        font-weight: bold;
        font-size: 9px;
    }

    .val {
        font-family: monospace;
        font-weight: bold;
        font-size: 10px;
        float: right;
    }

    .achv-box {
        text-align: center;
        margin-top: 8px;
        padding: 5px;
        border-radius: 4px;
        font-weight: bold;
        color: white;
    }

    .bg-green {
        background-color: #10b981;
    }

    .bg-blue {
        background-color: #3b82f6;
    }

    .bg-red {
        background-color: #ef4444;
    }

    .clear {
        clear: both;
    }
    </style>
</head>

<body>
    <div class="header">
        <h1>Laporan Pencapaian Individu Sales</h1>
        <p style="margin: 5px 0; font-size: 12px; color: #666;">Periode: {{ $periode }}</p>
    </div>

    <div class="card-container">
        @foreach($data as $item)
        <div class="card">
            <div class="card-header">
                <span class="sales-name">{{ $item['nama'] }}</span>
                <span class="sales-info">{{ $item['kode'] }} | {{ $item['cabang'] }}</span>
            </div>

            <div style="overflow: hidden; margin-bottom: 2px;">
                <span class="label">TARGET</span>
                <span class="val">Rp {{ number_format($item['target'], 0, ',', '.') }}</span>
            </div>
            <div style="overflow: hidden; margin-bottom: 2px;">
                <span class="label">REALISASI</span>
                <span class="val">Rp {{ number_format($item['realisasi'], 0, ',', '.') }}</span>
            </div>
            <div style="overflow: hidden; border-top: 1px dashed #eee; padding-top: 2px;">
                <span class="label">GAP</span>
                <span class="val" style="color: {{ $item['gap'] >= 0 ? 'green' : 'red' }}">
                    {{ number_format($item['gap'], 0, ',', '.') }}
                </span>
            </div>

            <div
                class="achv-box {{ $item['persen'] >= 100 ? 'bg-green' : ($item['persen'] >= 80 ? 'bg-blue' : 'bg-red') }}">
                ACHIEVEMENT: {{ number_format($item['persen'], 1) }}%
            </div>
        </div>
        @endforeach
        <div class="clear"></div>
    </div>
</body>

</html>