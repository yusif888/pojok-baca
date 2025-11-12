<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\BukuTamu;

class BukuTamuController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'unit' => 'required|string|max:255',
            'telepon' => 'required|string|max:255',
        ]);
        
        $validated['jenis_buku'] = 'fisik';
        // Simpan waktu kunjungan dalam zona WIB (Asia/Jakarta)
        $validated['tanggal_kunjungan'] = now('Asia/Jakarta');
        
        BukuTamu::create($validated);
        session(['tamu_terdaftar' => true]);
        
        return response()->json(['success' => true, 'redirect' => url('/beranda')]);
    }
}
