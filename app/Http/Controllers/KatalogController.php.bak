<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Pagination\LengthAwarePaginator;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        // Buka bebas tanpa wajib isi buku tamu; aman terhadap file tidak ditemukan
        $header = ['Judul Buku','Pengarang','Penerbit','Tahun','Kode Buku'];
        $rows = [];
        $file = public_path('katalog.xlsx');
        
        if (is_file($file)) {
            try {
                $spreadsheet = IOFactory::load($file);
                $sheet = $spreadsheet->getActiveSheet();
                $raw = $sheet->toArray();
                if (!empty($raw)) {
                    $header = array_shift($raw);
                    $rows = $raw;
                }
            } catch (\Throwable $e) {
                // Biarkan kosong, tampilkan halaman dengan pesan nanti jika perlu
                $rows = [];
            }
        }
        
        // Server-side search across all rows
        $q = trim((string)$request->query('q', ''));
        if ($q !== '') {
            $rows = array_values(array_filter($rows, function($row) use ($q){
                $text = strtolower(implode(' ', array_map(function($v){ return (string)$v; }, $row)));
                return strpos($text, strtolower($q)) !== false;
            }));
        }
        
        // Pagination with selectable per_page (10,20,30,50,100)
        $allowedPerPage = [10,20,30,50,100];
        $perPage = (int) $request->query('per_page', 20);
        if(!in_array($perPage, $allowedPerPage)) { $perPage = 20; }
        
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect($rows);
        $currentItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginator = new LengthAwarePaginator(
            $currentItems,
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => url()->current(), 'query' => $request->query()] // preserve other query params if any
        );
        
        return view('katalog', [
            'header' => $header,
            'rows' => $paginator,
            'perPage' => $perPage,
            'allowedPerPage' => $allowedPerPage,
        ]);
    }
}
