<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Presensi;
use App\Models\Pegawai;
use App\Models\Lokasi;
use Carbon\Carbon;

class PresensiApiController extends Controller
{
    /**
     * API: Check-in Presensi (Datang)
     */
    public function checkin(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'lokasi_id' => 'required|integer|exists:lokasi,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Cari pegawai berdasarkan username
        $pegawai = Pegawai::whereHas('user', function ($query) use ($request) {
            $query->where('username', $request->username);
        })->first();

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Pegawai tidak ditemukan',
            ], 404);
        }

        // Cek lokasi yang dipilih
        $lokasi = Lokasi::find($request->lokasi_id);
        if (!$lokasi) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi tidak ditemukan',
            ], 404);
        }

        // Validasi apakah pegawai ditugaskan ke lokasi ini
        $isLokasiAssigned = $pegawai->lokasis()->where('lokasi_id', $request->lokasi_id)->exists();
        if (!$isLokasiAssigned) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak ditugaskan di lokasi ini',
            ], 403);
        }

        // Cek apakah sudah check-in hari ini
        $today = Carbon::today('Asia/Makassar')->toDateString();
        $existingPresensi = Presensi::where('nik', $pegawai->nik)
            ->where('tanggal', $today)
            ->first();

        if ($existingPresensi) {
            if ($existingPresensi->jam_datang && !$existingPresensi->jam_pulang) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah check-in hari ini. Silakan check-out terlebih dahulu.',
                    'data' => [
                        'jam_datang' => $existingPresensi->jam_datang,
                    ],
                ], 400);
            } elseif ($existingPresensi->jam_datang && $existingPresensi->jam_pulang) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah check-in dan check-out hari ini.',
                    'data' => [
                        'jam_datang' => $existingPresensi->jam_datang,
                        'jam_pulang' => $existingPresensi->jam_pulang,
                    ],
                ], 400);
            }
        }

        // Simpan check-in
        $presensi = Presensi::updateOrCreate(
            [
                'nik' => $pegawai->nik,
                'tanggal' => $today,
            ],
            [
                'nama' => $pegawai->nama,
                'jam_datang' => Carbon::now('Asia/Makassar')->format('H:i:s'),
                'lokasi_id' => $request->lokasi_id,
                'skpd_id' => $pegawai->skpd_id,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil',
            'data' => [
                'presensi_id' => $presensi->id,
                'tanggal' => $presensi->tanggal,
                'jam_datang' => $presensi->jam_datang,
                'lokasi' => [
                    'id' => $lokasi->id,
                    'nama' => $lokasi->nama,
                    'latitude' => $lokasi->lat,
                    'longitude' => $lokasi->long,
                ],
            ],
        ], 200);
    }

    /**
     * API: Check-out Presensi (Pulang)
     */
    public function checkout(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Cari pegawai berdasarkan username
        $pegawai = Pegawai::whereHas('user', function ($query) use ($request) {
            $query->where('username', $request->username);
        })->first();

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Pegawai tidak ditemukan',
            ], 404);
        }

        // Cek presensi hari ini
        $today = Carbon::today('Asia/Makassar')->toDateString();
        $presensi = Presensi::where('nik', $pegawai->nik)
            ->where('tanggal', $today)
            ->first();

        if (!$presensi) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum check-in hari ini',
            ], 400);
        }

        if (!$presensi->jam_datang) {
            return response()->json([
                'success' => false,
                'message' => 'Data check-in tidak ditemukan',
            ], 400);
        }

        if ($presensi->jam_pulang) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah check-out hari ini',
                'data' => [
                    'jam_pulang' => $presensi->jam_pulang,
                ],
            ], 400);
        }

        // Validasi lokasi (gunakan lokasi yang sama saat check-in)
        $lokasi = Lokasi::find($presensi->lokasi_id);
        if (!$lokasi) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi presensi tidak ditemukan',
            ], 404);
        }

        // Update check-out
        $presensi->update([
            'jam_pulang' => Carbon::now('Asia/Makassar')->format('H:i:s'),
        ]);

        // Hitung durasi kerja
        $jamDatang = Carbon::parse($presensi->jam_datang);
        $jamPulang = Carbon::parse($presensi->jam_pulang);
        $durasi = $jamDatang->diff($jamPulang);
        $durasiJam = $durasi->h;
        $durasiMenit = $durasi->i;

        return response()->json([
            'success' => true,
            'message' => 'Check-out berhasil',
            'data' => [
                'presensi_id' => $presensi->id,
                'tanggal' => $presensi->tanggal,
                'jam_datang' => $presensi->jam_datang,
                'jam_pulang' => $presensi->jam_pulang,
                'durasi_kerja' => [
                    'jam' => $durasiJam,
                    'menit' => $durasiMenit,
                    'total' => "$durasiJam jam $durasiMenit menit",
                ],
                'lokasi' => [
                    'id' => $lokasi->id,
                    'nama' => $lokasi->nama,
                ],
            ],
        ], 200);
    }

    /**
     * API: Get Riwayat Presensi
     */
    public function history(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer|min:2020|max:2099',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Cari pegawai
        $pegawai = Pegawai::whereHas('user', function ($query) use ($request) {
            $query->where('username', $request->username);
        })->first();

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Pegawai tidak ditemukan',
            ], 404);
        }

        // Query presensi
        $query = Presensi::where('nik', $pegawai->nik)
            ->with('lokasi')
            ->orderBy('tanggal', 'desc');

        // Filter by month dan year jika ada
        if ($request->has('month')) {
            $query->whereMonth('tanggal', $request->month);
        }
        if ($request->has('year')) {
            $query->whereYear('tanggal', $request->year);
        }

        $presensiList = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat presensi berhasil diambil',
            'data' => [
                'pegawai' => [
                    'nik' => $pegawai->nik,
                    'nama' => $pegawai->nama,
                ],
                'filter' => [
                    'month' => $request->month ?? 'semua',
                    'year' => $request->year ?? 'semua',
                ],
                'presensi_count' => $presensiList->count(),
                'presensi_list' => $presensiList->map(function ($item) {
                    $durasi = null;
                    if ($item->jam_datang && $item->jam_pulang) {
                        $jamDatang = Carbon::parse($item->jam_datang);
                        $jamPulang = Carbon::parse($item->jam_pulang);
                        $diff = $jamDatang->diff($jamPulang);
                        $durasi = [
                            'jam' => $diff->h,
                            'menit' => $diff->i,
                            'total' => "{$diff->h} jam {$diff->i} menit",
                        ];
                    }

                    return [
                        'id' => $item->id,
                        'tanggal' => $item->tanggal,
                        'jam_datang' => $item->jam_datang,
                        'jam_pulang' => $item->jam_pulang,
                        'durasi_kerja' => $durasi,
                        'lokasi' => $item->lokasi ? [
                            'id' => $item->lokasi->id,
                            'nama' => $item->lokasi->nama,
                        ] : null,
                        'skpd_id' => $item->skpd_id,
                    ];
                }),
            ],
        ], 200);
    }

    /**
     * API: Get Lokasi Absensi yang Dimiliki Pegawai
     */
    public function getLokasiByPegawai(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Cari pegawai berdasarkan username
        $pegawai = Pegawai::whereHas('user', function ($query) use ($request) {
            $query->where('username', $request->username);
        })->first();

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Pegawai tidak ditemukan',
            ], 404);
        }

        // Ambil semua lokasi yang ditugaskan ke pegawai
        $lokasis = $pegawai->lokasis;

        return response()->json([
            'success' => true,
            'message' => 'Daftar lokasi absensi berhasil diambil',
            'data' => [
                'pegawai' => [
                    'nik' => $pegawai->nik,
                    'nama' => $pegawai->nama,
                ],
                'lokasi_count' => $lokasis->count(),
                'lokasi_list' => $lokasis->map(function ($lokasi) {
                    return [
                        'id' => $lokasi->id,
                        'nama' => $lokasi->nama,
                        'latitude' => $lokasi->lat,
                        'longitude' => $lokasi->long,
                        'radius' => $lokasi->radius,
                        'radius_in_meters' => $lokasi->radius . ' meter',
                        'skpd_id' => $lokasi->skpd_id,
                    ];
                }),
            ],
        ], 200);
    }

    /**
     * API: Get Lokasi Absensi berdasarkan pegawai_id
     */
    public function getLokasiByPegawaiId(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'pegawai_id' => 'required|integer|exists:pegawai,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Cari pegawai berdasarkan ID
        $pegawai = Pegawai::find($request->pegawai_id);

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Pegawai tidak ditemukan',
            ], 404);
        }

        // Ambil semua lokasi yang ditugaskan ke pegawai
        $lokasis = $pegawai->lokasis;

        return response()->json([
            'success' => true,
            'message' => 'Daftar lokasi absensi berhasil diambil',
            'data' => [
                'pegawai' => [
                    'id' => $pegawai->id,
                    'nik' => $pegawai->nik,
                    'nama' => $pegawai->nama,
                ],
                'lokasi_count' => $lokasis->count(),
                'lokasi_list' => $lokasis->map(function ($lokasi) {
                    return [
                        'id' => $lokasi->id,
                        'nama' => $lokasi->nama,
                        'latitude' => $lokasi->lat,
                        'longitude' => $lokasi->long,
                        'radius' => $lokasi->radius,
                        'radius_in_meters' => $lokasi->radius . ' meter',
                        'skpd_id' => $lokasi->skpd_id,
                    ];
                }),
            ],
        ], 200);
    }

    /**
     * API: Get Status Presensi Hari Ini
     */
    public function todayStatus(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Cari pegawai
        $pegawai = Pegawai::whereHas('user', function ($query) use ($request) {
            $query->where('username', $request->username);
        })->first();

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Pegawai tidak ditemukan',
            ], 404);
        }

        // Cek presensi hari ini
        $today = Carbon::today('Asia/Makassar')->toDateString();

        $presensi = Presensi::where('nik', $pegawai->nik)
            ->where('tanggal', $today)
            ->with('lokasi')
            ->first();

        $status = 'belum_checkin';
        $message = 'Anda belum check-in hari ini';

        if ($presensi) {
            if ($presensi->jam_datang && !$presensi->jam_pulang) {
                $status = 'sudah_checkin';
                $message = 'Anda sudah check-in hari ini. Silakan check-out.';
            } elseif ($presensi->jam_datang && $presensi->jam_pulang) {
                $status = 'sudah_checkout';
                $message = 'Anda sudah menyelesaikan presensi hari ini.';
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Status presensi hari ini',
            'data' => [
                'tanggal' => $today,
                'status' => $status,
                'message' => $message,
                'presensi' => $presensi ? [
                    'id' => $presensi->id,
                    'jam_datang' => $presensi->jam_datang,
                    'jam_pulang' => $presensi->jam_pulang,
                    'lokasi' => $presensi->lokasi ? [
                        'id' => $presensi->lokasi->id,
                        'nama' => $presensi->lokasi->nama,
                    ] : null,
                ] : null,
            ],
        ], 200);
    }

    /**
     * Hitung jarak antara dua koordinat (meter)
     * Menggunakan rumus Haversine
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Radius bumi dalam meter

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos($latFrom) * cos($latTo) *
            sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * asin(sqrt($a));

        return $earthRadius * $c; // Jarak dalam meter
    }
}
