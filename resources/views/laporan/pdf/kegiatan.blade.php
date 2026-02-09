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
            width: 100px;
        }
        .jam-masuk-cell {
            width: 100px;
        }
        .jam-pulang-cell {
            width: 100px;
        }
        .total-waktu-cell {
            width: 120px;
            text-align: center;
        }
        .keterangan-cell {
            width: 150px;
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
        <div class="info-row">
            <span class="info-label">Bulan:</span>
            <span>{{ $bulan }} {{ $tahun }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="no-cell">NO</th>
                <th class="tanggal-cell">TANGGAL</th>
                <th class="jam-masuk-cell">JAM MASUK</th>
                <th class="jam-pulang-cell">JAM PULANG</th>
                <th class="total-waktu-cell">TOTAL WAKTU DI KANTOR</th>
                <th class="keterangan-cell">KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $hadirCount = 0;
                $totalJam = 0;
                $totalHariKerja = 0;
            @endphp
            @foreach($kegiatan as $k)
                @if(!$k['is_weekend'])
                    @php $totalHariKerja++; @endphp
                @endif
                @if($k['keterangan'] === 'Hadir')
                    @php 
                        $hadirCount++; 
                        // Parse total waktu (e.g., "6 jam 30 menit")
                        if (preg_match('/(\d+)\s*jam/i', $k['total_waktu'], $matches)) {
                            $totalJam += $matches[1];
                        }
                    @endphp
                @endif
                <tr class="{{ $k['is_weekend'] ? 'weekend-row' : '' }}">
                    <td class="no-cell">{{ $no++ }}</td>
                    <td class="tanggal-cell">{{ date('d-m-Y', strtotime($k['date'])) }}</td>
                    <td class="jam-masuk-cell">{{ $k['jam_masuk'] }}</td>
                    <td class="jam-pulang-cell">{{ $k['jam_pulang'] }}</td>
                    <td class="total-waktu-cell">{{ $k['total_waktu'] }}</td>
                    <td class="keterangan-cell">{{ $k['keterangan'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <strong>Ringkasan:</strong> 
        Total Hari Kerja: {{ $totalHariKerja }} hari (Hari Sabtu & Minggu tidak dihitung) | 
        Hadir: {{ $hadirCount }} hari | 
        Tidak Hadir: {{ $totalHariKerja - $hadirCount }} hari | 
        Total Jam Kerja: {{ $totalJam }} jam
    </div>

    <div class="footer">
        <p>Total Data: {{ count($kegiatan) }} Hari</p>
    </div>
</body>
</html>