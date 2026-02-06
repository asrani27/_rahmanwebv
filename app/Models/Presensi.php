<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'presensi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nik',
        'nama',
        'tanggal',
        'jam_datang',
        'jam_pulang',
        'lokasi_id',
        'skpd_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Scope a query to only include presensi for a specific NIK.
     */
    public function scopeByNik($query, $nik)
    {
        return $query->where('nik', $nik);
    }

    /**
     * Scope a query to only include presensi for a specific month.
     */
    public function scopeMonth($query, $month)
    {
        return $query->whereMonth('tanggal', $month);
    }

    /**
     * Scope a query to only include presensi for a specific year.
     */
    public function scopeYear($query, $year)
    {
        return $query->whereYear('tanggal', $year);
    }

    /**
     * Get the pegawai that owns the presensi.
     */
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'nik', 'nik');
    }

    /**
     * Get the lokasi for the presensi.
     */
    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    /**
     * Get the SKPD for the presensi.
     */
    public function skpd()
    {
        return $this->belongsTo(Skpd::class);
    }
}
