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
        .no-cell {
            width: 50px;
            text-align: center;
        }
        .nama-cell {
            width: auto;
        }
        .skpd-cell {
            width: auto;
        }
        .lat-cell {
            width: 120px;
        }
        .long-cell {
            width: 120px;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Tanggal Cetak: {{ date('d-m-Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="no-cell">NO</th>
                <th class="nama-cell">NAMA LOKASI</th>
                <th class="skpd-cell">SKPD</th>
                <th class="lat-cell">LATITUDE</th>
                <th class="long-cell">LONGITUDE</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @foreach($lokasi as $l)
                <tr>
                    <td class="no-cell">{{ $no++ }}</td>
                    <td class="nama-cell">{{ $l->nama }}</td>
                    <td class="skpd-cell">{{ $l->skpd ? $l->skpd->nama : '-' }}</td>
                    <td class="lat-cell">{{ $l->lat }}</td>
                    <td class="long-cell">{{ $l->long }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($lokasi->count() == 0)
        <p style="text-align: center; margin-top: 30px; color: #666;">Tidak ada data lokasi presensi</p>
    @endif

    <div class="footer">
        <p>Total Data: {{ $lokasi->count() }} Lokasi Presensi</p>
    </div>
</body>
</html>