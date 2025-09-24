<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lampiran extends Model
{
    use HasFactory;

    // Mendefinisikan nama tabel secara eksplisit
    protected $table = 'lampiran';

    // Mendefinisikan kolom yang boleh diisi
    protected $fillable = [
        'laporan_id',
        'tipe_file',
        'path_file',
    ];
}