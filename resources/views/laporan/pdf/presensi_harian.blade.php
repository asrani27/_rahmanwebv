<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
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
            padding: 12px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #333;
            text-transform: uppercase;
        }
        td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .no-cell {
            width: 50px;
            text-align: center;
            font-weight: bold;
        }
        .nama-cell {
            width: auto;
        }
        .jam-cell {
            width: 120px;
            text-align: center;
        }
        .keterangan-cell {
            width: 150px;
            text-align: center;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
        }
        .summary {
            margin-top: 20px;
            padding: 10px;
            background-color: #e9f5ff;
            border-left: 4px solid #4a90e2;
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
            <span class="info-label">TANGGAL:</span>
            <span class="info-value">{{ date('d-m-Y', strtotime($tanggal)) }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="no-cell">NO</th>
                <th class="nama-cell">NAMA PEGAWAI</th>
                <th class="jam-cell">JAM MASUK</th>
                <th class="jam-cell">JAM PULANG</th>
                <th class="keterangan-cell">KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $hadirCount = 0;
                $tidakHadirCount = 0;
            @endphp
            @foreach($presensi_harian as $ph)
                @if($ph['keterangan'] === 'Hadir')
                    @php $hadirCount++; @endphp
                @else
                    @php $tidakHadirCount++; @endphp
                @endif
                <tr>
                    <td class="no-cell">{{ $no++ }}</td>
                    <td class="nama-cell">{{ $ph['pegawai']->nama }}</td>
                    <td class="jam-cell">{{ $ph['jam_datang'] }}</td>
                    <td class="jam-cell">{{ $ph['jam_pulang'] }}</td>
                    <td class="keterangan-cell">{{ $ph['keterangan'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <strong>Ringkasan:</strong> 
        Total Pegawai: {{ count($presensi_harian) }} | 
        Hadir: {{ $hadirCount }} pegawai | 
        Tidak Hadir: {{ $tidakHadirCount }} pegawai
    </div>

    <div class="footer">
        <p>Tanggal Cetak: {{ date('d-m-Y H:i:s') }}</p>
    </div>
</body>
</html>