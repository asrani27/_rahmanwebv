<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    protected $fillable = [
        'nik',
        'nama',
        'tgl_lahir',
        'jkel',
        'skpd_id',
        'telp',
        'alamat',
        'user_id',
    ];

    public function skpd()
    {
        return $this->belongsTo(Skpd::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function lokasis()
    {
        return $this->belongsToMany(Lokasi::class, 'lokasi_pegawai', 'pegawai_id', 'lokasi_id')
                    ->withTimestamps();
    }

    protected $casts = [
        'tgl_lahir' => 'date',
    ];
}
