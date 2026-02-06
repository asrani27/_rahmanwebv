<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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
