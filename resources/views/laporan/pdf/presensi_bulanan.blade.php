<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
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
            text-align: left;
            font-weight: bold;
            border: 1px solid #333;
            text-transform: uppercase;
            font-size: 10px;
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
            width: auto;
        }
        .skpd-cell {
            width: auto;
        }
        .number-cell {
            width: 80px;
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
            <span class="info-label">BULAN:</span>
            <span class="info-value">{{ $bulan }}</span>
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
                <th class="nama-cell">NAMA</th>
                <th class="skpd-cell">SKPD</th>
                <th class="number-cell">TOTAL HARI KERJA</th>
                <th class="number-cell">JUMLAH HADIR</th>
                <th class="number-cell">JUMLAH TIDAK HADIR</th>
                <th class="number-cell">JUMLAH IZIN</th>
                <th class="number-cell">JUMLAH SAKIT</th>
                <th class="number-cell">PERSENTASE</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $totalHadir = 0;
                $totalTidakHadir = 0;
            @endphp
            @foreach($presensi_bulanan as $pb)
                @php $totalHadir += $pb['jumlah_hadir']; @endphp
                @php $totalTidakHadir += $pb['jumlah_tidak_hadir']; @endphp
                <tr>
                    <td class="no-cell">{{ $no++ }}</td>
                    <td class="nama-cell">{{ $pb['pegawai']->nama }}</td>
                    <td class="skpd-cell">{{ $pb['pegawai']->skpd->nama ?? '-' }}</td>
                    <td class="number-cell">{{ $pb['total_hari_kerja'] }}</td>
                    <td class="number-cell">{{ $pb['jumlah_hadir'] }}</td>
                    <td class="number-cell">{{ $pb['jumlah_tidak_hadir'] }}</td>
                    <td class="number-cell">{{ $pb['jumlah_izin'] }}</td>
                    <td class="number-cell">{{ $pb['jumlah_sakit'] }}</td>
                    <td class="number-cell">{{ $pb['persentase'] }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <strong>Ringkasan:</strong> 
        Total Pegawai: {{ count($presensi_bulanan) }} | 
        Total Hadir: {{ $totalHadir }} hari | 
        Total Tidak Hadir: {{ $totalTidakHadir }} hari
    </div>

    <div class="footer">
        <p>Tanggal Cetak: {{ date('d-m-Y H:i:s') }}</p>
    </div>
</body>
</html>