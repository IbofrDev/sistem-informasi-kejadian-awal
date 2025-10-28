<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lampiran extends Model
{
    use HasFactory;

    protected $table = 'lampiran';

    protected $fillable = [
        'laporan_id',
        'tipe_file',
        'path_file',
    ];

    public function laporan()
    {
        return $this->belongsTo(LaporanKejadian::class, 'laporan_id');
    }
}