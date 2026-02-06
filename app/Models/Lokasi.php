<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    protected $table = 'lokasi';
    protected $fillable = [
        'skpd_id',
        'nama',
        'lat',
        'long',
        'radius',
    ];

    public function skpd()
    {
        return $this->belongsTo(Skpd::class);
    }

    public function pegawais()
    {
        return $this->belongsToMany(Pegawai::class, 'lokasi_pegawai', 'lokasi_id', 'pegawai_id')
                    ->withTimestamps();
    }
}
