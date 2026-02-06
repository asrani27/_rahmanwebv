<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pegawai;

class DashboardController extends Controller
{
    public function index()
    {
        // Get counts for each module
        $data = [
            'totalUsers' => User::count(),
            'totalPegawai' => Pegawai::count(),
            
            // Get recent data
            'recentPegawai' => Pegawai::latest()->take(5)->get(),
        ];
        
        return view('dashboard.index', $data);
    }

    public function skpdDashboard()
    {
        // Get counts for SKPD dashboard
        $data = [
            'totalPegawai' => Pegawai::count(),
        ];
        
        return view('skpd.index', $data);
    }
}
