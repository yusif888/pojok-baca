<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuTamu extends Model
{
    protected $fillable = ['nama', 'tanggal', 'unit', 'telepon', 'jenis_buku', 'tanggal_kunjungan'];
}
