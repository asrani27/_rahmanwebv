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
            margin: 0 0 10px 0;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0;
            font-size: 11px;
        }
        .info-section {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .info-row {
            margin: 8px 0;
            display: flex;
        }
        .info-label {
            font-weight: bold;
            min-width: 150px;
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
        }
        td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr.weekend-row {
            background-color: #ffd9e1 !important;
        }
        .no-cell {
            width: 50px;
            text-align: center;
        }
        .tanggal-cell {
            width: 120px;
        }
        .jam-cell {
            width: 120px;
        }
        .lokasi-cell {
            width: auto;
        }
        .keterangan-cell {
            width: 100px;
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
        <p>Periode: {{ $bulan }} {{ $tahun }}</p>
        <p>Tanggal Cetak: {{ date('d-m-Y H:i:s') }}</p>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Nama Pegawai:</span>
            <span>{{ $pegawai->nama }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">NIK:</span>
            <span>{{ $pegawai->nik }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">SKPD:</span>
            <span>{{ $pegawai->skpd->nama ?? '-' }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="no-cell">NO</th>
                <th class="tanggal-cell">TANGGAL</th>
                <th class="jam-cell">JAM</th>
                <th class="lokasi-cell">LOKASI</th>
                <th class="keterangan-cell">KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $hadirCount = 0;
                $totalHariKerja = 0;
            @endphp
            @foreach($presensi as $p)
                @if(!$p['is_weekend'])
                    @php $totalHariKerja++; @endphp
                @endif
                @if($p['keterangan'] === 'Hadir')
                    @php $hadirCount++; @endphp
                @endif
                <tr class="{{ $p['is_weekend'] ? 'weekend-row' : '' }}">
                    <td class="no-cell">{{ $no++ }}</td>
                    <td class="tanggal-cell">{{ date('d-m-Y', strtotime($p['date'])) }}</td>
                    <td class="jam-cell">{{ $p['jam'] }}</td>
                    <td class="lokasi-cell">{{ $p['lokasi'] }}</td>
                    <td class="keterangan-cell">{{ $p['keterangan'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <strong>Ringkasan:</strong> 
        Total Hari Kerja: {{ $totalHariKerja }} hari (Hari Sabtu & Minggu tidak dihitung) | 
        Hadir: {{ $hadirCount }} hari | 
        Tidak Hadir: {{ $totalHariKerja - $hadirCount }} hari
    </div>

    <div class="footer">
        <p>Total Data: {{ count($presensi) }} Hari</p>
    </div>
</body>
</html>