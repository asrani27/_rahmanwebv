<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            size: 410mm 297mm;
            /* A4 default */
            margin: 10mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 18px;
            margin: 0 0 20px 0;
            font-weight: bold;
            text-transform: uppercase;
        }

        .info-section {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .info-row {
            margin: 10px 0;
            display: flex;
            align-items: center;
        }

        .info-label {
            font-weight: bold;
            min-width: 100px;
            margin-right: 10px;
        }

        .info-value {
            flex: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background-color: #4a4a4a;
            color: white;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #333;
            text-transform: uppercase;
            font-size: 9px;
        }

        td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 10px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .no-cell {
            width: 40px;
            text-align: center;
            font-weight: bold;
        }

        .nama-cell {
            width: 200px;
            font-weight: bold;
        }

        .percentage-cell {
            width: 60px;
            text-align: center;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
        }

        .summary {
            margin-top: 20px;
            padding: 10px;
            background-color: #e9f5ff;
            border-left: 4px solid #4a90e2;
            font-size: 10px;
        }

        .high-percentage {
            color: #008000;
            font-weight: bold;
        }

        .medium-percentage {
            color: #ff6600;
            font-weight: bold;
        }

        .low-percentage {
            color: #cc0000;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $title }}</h1>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">SKPD:</span>
            <span class="info-value">{{ $skpd->nama }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">TAHUN:</span>
            <span class="info-value">{{ $tahun }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="no-cell">NO</th>
                <th class="nama-cell">NAMA PEGAWAI</th>
                <th class="percentage-cell">JANUARI (%)</th>
                <th class="percentage-cell">FEBRUARI (%)</th>
                <th class="percentage-cell">MARET (%)</th>
                <th class="percentage-cell">APRIL (%)</th>
                <th class="percentage-cell">MEI (%)</th>
                <th class="percentage-cell">JUNI (%)</th>
                <th class="percentage-cell">JULI (%)</th>
                <th class="percentage-cell">AGUSTUS (%)</th>
                <th class="percentage-cell">SEPTEMBER (%)</th>
                <th class="percentage-cell">OKTOBER (%)</th>
                <th class="percentage-cell">NOVEMBER (%)</th>
                <th class="percentage-cell">DESEMBER (%)</th>
            </tr>
        </thead>
        <tbody>
            @php
            $no = 1;
            $tahunanTotal = 0;
            $tahunanCount = 0;
            @endphp
            @foreach($presensi_tahunan as $pt)
            <tr>
                <td class="no-cell">{{ $no++ }}</td>
                <td class="nama-cell">{{ $pt['pegawai']->nama }}</td>
                @foreach($pt['bulanan'] as $index => $persentase)
                @php
                $class = '';
                if ($persentase >= 80) {
                $class = 'high-percentage';
                } elseif ($persentase >= 50) {
                $class = 'medium-percentage';
                } else {
                $class = 'low-percentage';
                }
                $tahunanTotal += $persentase;
                $tahunanCount++;
                @endphp
                <td class="percentage-cell {{ $class }}">{{ number_format($persentase, 2) }}%</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <strong>Ringkasan:</strong>
        Total Pegawai: {{ count($presensi_tahunan) }} |
        Rata-rata Kehadiran Tahunan: {{ $tahunanCount > 0 ? number_format($tahunanTotal / $tahunanCount, 2) : 0 }}%
    </div>

    <div class="footer">
        <p>Tanggal Cetak: {{ date('d-m-Y H:i:s') }}</p>
    </div>
</body>

</html>