<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuTamuController;
use App\Http\Controllers\KatalogController;
use App\Models\BukuTamu;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/beranda', function () {
    $isAdmin = session('is_admin', false);
    return view('beranda', compact('isAdmin'));
});

// Halaman buku tamu (tabel daftar tamu)
Route::get('/buku-tamu', function(Request $request) {
    $isAdmin = session('is_admin', false);
    // Paginate 20 per halaman untuk publik
    $tamus = BukuTamu::orderByDesc('id')->paginate(20);
    return view('buku-tamu', compact('tamus', 'isAdmin'));
});

// Upload/update katalog (admin only)
Route::post('/admin/katalog/upload', function(Request $request) {
    if(!session('is_admin', false)) {
        return redirect('/login');
    }
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls'
    ]);
    try {
        $path = $request->file('file')->getRealPath();
        // Validasi dasar: pastikan sheet pertama punya header minimal 3 kolom
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();
        if(empty($data) || count($data[0]) < 3) {
            return back()->withErrors(['file' => 'Format file tidak valid atau header kurang.']);
        }
        // Simpan sebagai katalog.xlsx di public
        $target = public_path('katalog.xlsx');
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($target);
        return back()->with('status', 'Katalog berhasil diperbarui.');
    } catch(\Throwable $e) {
        return back()->withErrors(['file' => 'Gagal memproses file: '.$e->getMessage()]);
    }
});

// Login admin
Route::get('/login', function() { return view('auth.login'); });
Route::post('/login', function(Request $request) {
    $request->validate(['username'=>'required','password'=>'required']);
    $admin = DB::table('admins')->where('username', $request->username)->first();
    if($admin && Hash::check($request->password, $admin->password)) {
        session(['is_admin'=>true]);
        return redirect('/beranda');
    }
    return back()->withErrors(['username'=>'Login gagal']);
});
Route::post('/logout', function() {
    session()->forget('is_admin');
    return redirect('/');
});

// Halaman admin buku tamu (tanpa sensor, perlu login)
Route::get('/admin/buku-tamu', function(Request $request) {
    if(!session('is_admin', false)) {
        return redirect('/login');
    }
    // Filters
    $month = $request->query('month');
    $year = $request->query('year');
    $allowedPerPage = [10,20,30,50,100];
    $perPage = (int) $request->query('per_page', 10);
    if(!in_array($perPage, $allowedPerPage)) { $perPage = 10; }

    $query = BukuTamu::query();
    if($month) { $query->whereMonth('tanggal_kunjungan', (int)$month); }
    if($year) { $query->whereYear('tanggal_kunjungan', (int)$year); }

    $query->orderByDesc('tanggal_kunjungan')->orderByDesc('id');
    $tamus = $query->paginate($perPage)->withQueryString();

    // Build year options based on data range
    $firstDate = BukuTamu::whereNotNull('tanggal_kunjungan')
        ->orderBy('tanggal_kunjungan', 'asc')
        ->value('tanggal_kunjungan');
    $startYear = $firstDate ? Carbon::parse($firstDate)->year : Carbon::now('Asia/Jakarta')->year;
    $currentYear = Carbon::now('Asia/Jakarta')->year;
    $years = range($startYear, $currentYear);

    return view('admin.buku-tamu', compact('tamus','years','month','year','perPage','allowedPerPage'));
});

// Hapus satu entri buku tamu (admin only)
Route::delete('/admin/buku-tamu/{id}', function(Request $request, $id) {
    if(!session('is_admin', false)) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }
    $deleted = BukuTamu::where('id', $id)->delete();
    return response()->json(['ok' => (bool)$deleted]);
});

Route::post('/buku-tamu', [BukuTamuController::class, 'store']);
Route::get('/katalog', [KatalogController::class, 'index']);
Route::get('/buku-fisik', function(Request $request) {
    session(['jenis_buku' => 'fisik']);
    
    $header = ['Judul Buku','Pengarang','Penerbit','Tahun','Kode Buku'];
    $rows = [];
    $file = public_path('katalog.xlsx');

    if (is_file($file)) {
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $raw = $sheet->toArray();
            if (!empty($raw)) {
                $header = array_shift($raw);
                $rows = $raw;
            }
        } catch (\Throwable $e) {
            $rows = [];
        }
    }

    $q = trim((string)$request->query('q', ''));
    if ($q !== '') {
        $rows = array_values(array_filter($rows, function($row) use ($q){
            $text = strtolower(implode(' ', array_map(function($v){ return (string)$v; }, $row)));
            return strpos($text, strtolower($q)) !== false;
        }));
    }

    $allowedPerPage = [10,20,30,50,100];
    $perPage = (int) $request->query('per_page', 20);
    if(!in_array($perPage, $allowedPerPage)) { $perPage = 20; }

    $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
    $collection = collect($rows);
    $currentItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();
    $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
        $currentItems,
        $collection->count(),
        $perPage,
        $currentPage,
        ['path' => url()->current(), 'query' => $request->query()]
    );

    return view('buku-fisik', [
        'header' => $header,
        'rows' => $paginator,
        'perPage' => $perPage,
        'allowedPerPage' => $allowedPerPage,
    ]);
});

// Admin katalog preview (separate page before public) - simple read + paginate + search
Route::get('/admin/katalog', function(Request $request) {
    if(!session('is_admin', false)) { return redirect('/login'); }
    $header = ['Judul Buku','Pengarang','Penerbit','Tahun','Kode Buku'];
    $rows = [];
    $file = public_path('katalog.xlsx');
    if (is_file($file)) {
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $raw = $sheet->toArray();
            if(!empty($raw)) { $header = array_shift($raw); $rows = $raw; }
        } catch (\Throwable $e) {
            $rows = [];
        }
    }
    $q = trim((string)$request->query('q', ''));
    if($q !== '') {
        $rows = array_values(array_filter($rows, function($row) use ($q){
            $text = strtolower(implode(' ', array_map(fn($v)=> (string)$v, $row)));
            return strpos($text, strtolower($q)) !== false;
        }));
    }
    $perPage = 20; // admin preview per page
    $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
    $collection = collect($rows);
    $currentItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();
    $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
        $currentItems,
        $collection->count(),
        $perPage,
        $currentPage,
        ['path' => url()->current(), 'query' => $request->query()]
    );
    return view('admin.katalog', [
        'header' => $header,
        'rows' => $paginator,
        'perPage' => $perPage,
        'q' => $q,
        'fileExists' => is_file($file)
    ]);
});

// Placeholder Buku Digital (tanpa popup)
Route::get('/buku-digital', function() {
    session(['jenis_buku' => 'digital']);
    return view('buku-digital');
});

Route::get('/profil', function() {
    return view('profil');
});

