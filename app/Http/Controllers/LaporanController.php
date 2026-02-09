<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pegawai;
use App\Models\Lokasi;
use App\Models\Presensi;
use App\Models\Skpd;

class LaporanController extends Controller
{
    /**
     * Display the laporan page.
     */
    public function index()
    {
        return view('dashboard.laporan.index');
    }

    /**
     * Generate and export PDF report.
     */
    public function exportPdf(Request $request)
    {
        $type = $request->input('type');

        if ($type === 'pegawai') {
            $pegawai = Pegawai::with('skpd')
                ->orderBy('skpd_id')
                ->orderBy('nama')
                ->get();

            $data = [
                'title' => 'Laporan Data Pegawai',
                'pegawai' => $pegawai,
            ];

            $pdf = PDF::loadView('laporan.pdf.pegawai', $data);

            return $pdf->stream('laporan-pegawai.pdf');
        }

        if ($type === 'lokasi') {
            $lokasi = Lokasi::with('skpd')
                ->orderBy('skpd_id')
                ->orderBy('nama')
                ->get();

            $data = [
                'title' => 'Laporan Data Lokasi Presensi',
                'lokasi' => $lokasi,
            ];

            $pdf = PDF::loadView('laporan.pdf.lokasi', $data);

            return $pdf->stream('laporan-lokasi.pdf');
        }

        if ($type === 'presensi') {
            $pegawai = Pegawai::with('skpd')->findOrFail($request->input('pegawai_id'));
            $bulan = $request->input('bulan');
            $tahun = $request->input('tahun');

            // Get all days in the selected month
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
            $calendar = [];

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = sprintf('%04d-%02d-%02d', $tahun, $bulan, $day);
                $calendar[] = [
                    'date' => $date,
                    'day' => $day,
                ];
            }

            // Get presensi data for this employee in this month/year
            $presensiData = Presensi::where('nik', $pegawai->nik)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->orderBy('tanggal')
                ->with('lokasi')
                ->get()
                ->map(function ($item) {
                    $item->date_only = date('Y-m-d', strtotime($item->tanggal));
                    return $item;
                })
                ->keyBy('date_only');

            // Match calendar with presensi data
            $presensiList = [];
            foreach ($calendar as $cal) {
                $presensi = $presensiData->get($cal['date']);
                $dayOfWeek = date('N', strtotime($cal['date'])); // 1 = Monday, 7 = Sunday
                $isWeekend = ($dayOfWeek == 6 || $dayOfWeek == 7); // Saturday (6) or Sunday (7)
                
                $keterangan = $isWeekend ? 'Weekend' : ($presensi ? 'Hadir' : 'Tidak Hadir');
                
                $presensiList[] = [
                    'date' => $cal['date'],
                    'day' => $cal['day'],
                    'jam' => $presensi ? $presensi->jam_datang : '-',
                    'lokasi' => $presensi && $presensi->lokasi ? $presensi->lokasi->nama : '-',
                    'keterangan' => $keterangan,
                    'is_weekend' => $isWeekend,
                ];
            }

            $data = [
                'title' => 'Laporan Presensi Datang',
                'pegawai' => $pegawai,
                'bulan' => $this->getMonthName($bulan),
                'tahun' => $tahun,
                'presensi' => $presensiList,
            ];

            $pdf = PDF::loadView('laporan.pdf.presensi', $data);

            return $pdf->stream('laporan-presensi.pdf');
        }

        if ($type === 'presensi_pulang') {
            $pegawai = Pegawai::with('skpd')->findOrFail($request->input('pegawai_id'));
            $bulan = $request->input('bulan');
            $tahun = $request->input('tahun');

            // Get all days in the selected month
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
            $calendar = [];

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = sprintf('%04d-%02d-%02d', $tahun, $bulan, $day);
                $calendar[] = [
                    'date' => $date,
                    'day' => $day,
                ];
            }

            // Get presensi data for this employee in this month/year
            $presensiData = Presensi::where('nik', $pegawai->nik)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->orderBy('tanggal')
                ->with('lokasi')
                ->get()
                ->map(function ($item) {
                    $item->date_only = date('Y-m-d', strtotime($item->tanggal));
                    return $item;
                })
                ->keyBy('date_only');

            // Match calendar with presensi data
            $presensiList = [];
            foreach ($calendar as $cal) {
                $presensi = $presensiData->get($cal['date']);
                $dayOfWeek = date('N', strtotime($cal['date'])); // 1 = Monday, 7 = Sunday
                $isWeekend = ($dayOfWeek == 6 || $dayOfWeek == 7); // Saturday (6) or Sunday (7)
                
                $keterangan = $isWeekend ? 'Weekend' : ($presensi ? 'Hadir' : 'Tidak Hadir');
                
                $presensiList[] = [
                    'date' => $cal['date'],
                    'day' => $cal['day'],
                    'jam' => $presensi ? $presensi->jam_pulang : '-',
                    'lokasi' => $presensi && $presensi->lokasi ? $presensi->lokasi->nama : '-',
                    'keterangan' => $keterangan,
                    'is_weekend' => $isWeekend,
                ];
            }

            $data = [
                'title' => 'Laporan Presensi Pulang',
                'pegawai' => $pegawai,
                'bulan' => $this->getMonthName($bulan),
                'tahun' => $tahun,
                'presensi' => $presensiList,
            ];

            $pdf = PDF::loadView('laporan.pdf.presensi', $data);

            return $pdf->stream('laporan-presensi-pulang.pdf');
        }

        if ($type === 'kegiatan') {
            $pegawai = Pegawai::with('skpd')->findOrFail($request->input('pegawai_id'));
            $bulan = $request->input('bulan');
            $tahun = $request->input('tahun');

            // Get all days in the selected month
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
            $calendar = [];

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = sprintf('%04d-%02d-%02d', $tahun, $bulan, $day);
                $calendar[] = [
                    'date' => $date,
                    'day' => $day,
                ];
            }

            // Get presensi data for this employee in this month/year
            $presensiData = Presensi::where('nik', $pegawai->nik)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->orderBy('tanggal')
                ->with('lokasi')
                ->get()
                ->map(function ($item) {
                    $item->date_only = date('Y-m-d', strtotime($item->tanggal));
                    return $item;
                })
                ->keyBy('date_only');

            // Match calendar with presensi data
            $kegiatanList = [];
            foreach ($calendar as $cal) {
                $presensi = $presensiData->get($cal['date']);
                $dayOfWeek = date('N', strtotime($cal['date'])); // 1 = Monday, 7 = Sunday
                $isWeekend = ($dayOfWeek == 6 || $dayOfWeek == 7); // Saturday (6) or Sunday (7)
                
                $jamDatang = '-';
                $jamPulang = '-';
                $totalWaktu = '-';
                $keterangan = '-';
                
                if ($isWeekend) {
                    $keterangan = 'Weekend';
                } elseif ($presensi) {
                    $jamDatang = $presensi->jam_datang ?? '-';
                    $jamPulang = $presensi->jam_pulang ?? '-';
                    
                    // Check for incomplete attendance
                    $datangKosong = ($jamDatang === '-' || $jamDatang === '00:00:00' || $jamDatang === null);
                    $pulangKosong = ($jamPulang === '-' || $jamPulang === '00:00:00' || $jamPulang === null);
                    
                    if ($datangKosong && $pulangKosong) {
                        $keterangan = 'Tidak Hadir';
                    } elseif ($datangKosong) {
                        $keterangan = 'Datang tidak absen';
                    } elseif ($pulangKosong) {
                        $keterangan = 'Pulang tidak absen';
                    } else {
                        // Calculate total working hours
                        $timeDatang = strtotime($jamDatang);
                        $timePulang = strtotime($jamPulang);
                        $diff = $timePulang - $timeDatang;
                        $hours = floor($diff / 3600);
                        $minutes = floor(($diff % 3600) / 60);
                        $totalWaktu = $hours . ' jam ' . $minutes . ' menit';
                        $keterangan = 'Hadir';
                    }
                } else {
                    $keterangan = 'Tidak Hadir';
                }
                
                $kegiatanList[] = [
                    'date' => $cal['date'],
                    'day' => $cal['day'],
                    'jam_masuk' => $jamDatang,
                    'jam_pulang' => $jamPulang,
                    'total_waktu' => $totalWaktu,
                    'keterangan' => $keterangan,
                    'is_weekend' => $isWeekend,
                ];
            }

            $data = [
                'title' => 'Laporan Kegiatan Pegawai',
                'pegawai' => $pegawai,
                'bulan' => $this->getMonthName($bulan),
                'tahun' => $tahun,
                'kegiatan' => $kegiatanList,
            ];

            $pdf = PDF::loadView('laporan.pdf.kegiatan', $data)
                ->setPaper('a4', 'landscape');

            return $pdf->stream('laporan-kegiatan.pdf');
        }

        if ($type === 'presensi_harian') {
            $skpd = Skpd::findOrFail($request->input('skpd_id'));
            $tanggal = $request->input('tanggal');

            // Get all employees from this SKPD
            $pegawaiList = Pegawai::where('skpd_id', $skpd->id)
                ->orderBy('nama')
                ->get();

            // Get presensi data for this date
            $presensiData = Presensi::whereDate('tanggal', $tanggal)
                ->whereIn('nik', $pegawaiList->pluck('nik'))
                ->get()
                ->keyBy('nik');

            // Build the presensi list with keterangan
            $presensiHarianList = [];
            foreach ($pegawaiList as $pegawai) {
                $presensi = $presensiData->get($pegawai->nik);
                $jamDatang = '-';
                $jamPulang = '-';
                $keterangan = '-';

                if ($presensi) {
                    $jamDatang = $presensi->jam_datang ?? '-';
                    $jamPulang = $presensi->jam_pulang ?? '-';

                    // Check for incomplete attendance
                    $datangKosong = ($jamDatang === '-' || $jamDatang === '00:00:00' || $jamDatang === null);
                    $pulangKosong = ($jamPulang === '-' || $jamPulang === '00:00:00' || $jamPulang === null);

                    if ($datangKosong && $pulangKosong) {
                        $keterangan = 'Tidak Hadir';
                    } elseif ($datangKosong) {
                        $keterangan = 'Datang tidak absen';
                    } elseif ($pulangKosong) {
                        $keterangan = 'Pulang tidak absen';
                    } else {
                        $keterangan = 'Hadir';
                    }
                } else {
                    $keterangan = 'Tidak Hadir';
                }

                $presensiHarianList[] = [
                    'pegawai' => $pegawai,
                    'jam_datang' => $jamDatang,
                    'jam_pulang' => $jamPulang,
                    'keterangan' => $keterangan,
                ];
            }

            $data = [
                'title' => 'LAPORAN PRESENSI HARIAN',
                'skpd' => $skpd,
                'tanggal' => $tanggal,
                'presensi_harian' => $presensiHarianList,
            ];

            $pdf = PDF::loadView('laporan.pdf.presensi_harian', $data);

            return $pdf->stream('laporan-presensi-harian.pdf');
        }

        if ($type === 'presensi_bulanan') {
            $skpd = Skpd::findOrFail($request->input('skpd_id'));
            $bulan = $request->input('bulan');
            $tahun = $request->input('tahun');

            // Calculate total working days (excluding weekends)
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
            $totalHariKerja = 0;
            
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = sprintf('%04d-%02d-%02d', $tahun, $bulan, $day);
                $dayOfWeek = date('N', strtotime($date)); // 1 = Monday, 7 = Sunday
                if ($dayOfWeek != 6 && $dayOfWeek != 7) { // Not Saturday (6) or Sunday (7)
                    $totalHariKerja++;
                }
            }

            // Get all employees from this SKPD
            $pegawaiList = Pegawai::where('skpd_id', $skpd->id)
                ->orderBy('nama')
                ->get();

            // Build the presensi bulanan list
            $presensiBulananList = [];
            foreach ($pegawaiList as $pegawai) {
                // Get presensi data for this employee in this month/year
                $presensiData = Presensi::where('nik', $pegawai->nik)
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->get();

                // Count hadir days (excluding weekends)
                $jumlahHadir = 0;
                foreach ($presensiData as $presensi) {
                    $date = $presensi->tanggal;
                    $dayOfWeek = date('N', strtotime($date)); // 1 = Monday, 7 = Sunday
                    
                    if ($dayOfWeek != 6 && $dayOfWeek != 7) { // Not weekend
                        // Check if both jam_datang and jam_pulang are present
                        $jamDatang = $presensi->jam_datang ?? null;
                        $jamPulang = $presensi->jam_pulang ?? null;
                        
                        $datangKosong = ($jamDatang === null || $jamDatang === '00:00:00');
                        $pulangKosong = ($jamPulang === null || $jamPulang === '00:00:00');
                        
                        if (!$datangKosong && !$pulangKosong) {
                            $jumlahHadir++;
                        }
                    }
                }

                $jumlahTidakHadir = $totalHariKerja - $jumlahHadir;
                $jumlahIzin = 0;
                $jumlahSakit = 0;
                $persentase = $totalHariKerja > 0 ? round(($jumlahHadir / $totalHariKerja) * 100, 2) : 0;

                $presensiBulananList[] = [
                    'pegawai' => $pegawai,
                    'total_hari_kerja' => $totalHariKerja,
                    'jumlah_hadir' => $jumlahHadir,
                    'jumlah_tidak_hadir' => $jumlahTidakHadir,
                    'jumlah_izin' => $jumlahIzin,
                    'jumlah_sakit' => $jumlahSakit,
                    'persentase' => $persentase,
                ];
            }

            $data = [
                'title' => 'LAPORAN PRESENSI BULANAN',
                'skpd' => $skpd,
                'bulan' => $this->getMonthName($bulan),
                'tahun' => $tahun,
                'presensi_bulanan' => $presensiBulananList,
            ];

            $pdf = PDF::loadView('laporan.pdf.presensi_bulanan', $data)
                ->setPaper('a4', 'landscape');

            return $pdf->stream('laporan-presensi-bulanan.pdf');
        }

        if ($type === 'presensi_tahunan') {
            $skpd = Skpd::findOrFail($request->input('skpd_id'));
            $tahun = $request->input('tahun');

            // Get all employees from this SKPD
            $pegawaiList = Pegawai::where('skpd_id', $skpd->id)
                ->orderBy('nama')
                ->get();

            // Build the presensi tahunan list with monthly percentages
            $presensiTahunanList = [];
            foreach ($pegawaiList as $pegawai) {
                $bulananPersentase = [];
                
                // Calculate for each month (1-12)
                for ($bulan = 1; $bulan <= 12; $bulan++) {
                    // Calculate total working days (excluding weekends)
                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
                    $totalHariKerja = 0;
                    
                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $date = sprintf('%04d-%02d-%02d', $tahun, $bulan, $day);
                        $dayOfWeek = date('N', strtotime($date)); // 1 = Monday, 7 = Sunday
                        if ($dayOfWeek != 6 && $dayOfWeek != 7) { // Not Saturday (6) or Sunday (7)
                            $totalHariKerja++;
                        }
                    }

                    // Get presensi data for this employee in this month/year
                    $presensiData = Presensi::where('nik', $pegawai->nik)
                        ->whereMonth('tanggal', $bulan)
                        ->whereYear('tanggal', $tahun)
                        ->get();

                    // Count hadir days (excluding weekends)
                    $jumlahHadir = 0;
                    foreach ($presensiData as $presensi) {
                        $date = $presensi->tanggal;
                        $dayOfWeek = date('N', strtotime($date)); // 1 = Monday, 7 = Sunday
                        
                        if ($dayOfWeek != 6 && $dayOfWeek != 7) { // Not weekend
                            // Check if both jam_datang and jam_pulang are present
                            $jamDatang = $presensi->jam_datang ?? null;
                            $jamPulang = $presensi->jam_pulang ?? null;
                            
                            $datangKosong = ($jamDatang === null || $jamDatang === '00:00:00');
                            $pulangKosong = ($jamPulang === null || $jamPulang === '00:00:00');
                            
                            if (!$datangKosong && !$pulangKosong) {
                                $jumlahHadir++;
                            }
                        }
                    }

                    $persentase = $totalHariKerja > 0 ? round(($jumlahHadir / $totalHariKerja) * 100, 2) : 0;
                    $bulananPersentase[] = $persentase;
                }

                $presensiTahunanList[] = [
                    'pegawai' => $pegawai,
                    'bulanan' => $bulananPersentase,
                ];
            }

            $data = [
                'title' => 'LAPORAN PRESENSI TAHUNAN',
                'skpd' => $skpd,
                'tahun' => $tahun,
                'presensi_tahunan' => $presensiTahunanList,
            ];

            $pdf = PDF::loadView('laporan.pdf.presensi_tahunan', $data)
                ->setPaper('a4', 'landscape');

            return $pdf->stream('laporan-presensi-tahunan.pdf');
        }

        return response()->json([
            'message' => 'Laporan berhasil dibuat',
            'data' => [],
        ]);
    }

    /**
     * Get month name in Indonesian.
     */
    private function getMonthName($bulan)
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $months[$bulan] ?? '';
    }
}
