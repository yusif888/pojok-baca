<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Katalog Buku</title>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .admin-katalog .header-menu { display:flex; gap:18px; justify-content:center; background:rgba(255,255,255,0.92); box-shadow:0 2px 16px rgba(44,200,180,0.10); padding:18px 0; border-bottom-left-radius:24px; border-bottom-right-radius:24px; }
        .admin-katalog .header-menu a{ text-decoration:none; color:#2d3a4a; font-weight:700; padding:10px 16px; border-radius:8px; }
        .admin-katalog .header-menu a.active{ color:#2ec4b6; }
        .upload-label{ background: linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; padding:12px 16px; border-radius:10px; font-size:14px; cursor:pointer; transition:background .25s, transform .25s; display:inline-block; text-decoration:none; white-space:nowrap; }
        .upload-label:hover{ background: linear-gradient(135deg,#1e40af,#1d4ed8); }
        .upload-label:active{ transform:scale(.95); }
    .search-row{ display:flex; gap:12px; align-items:center; max-width:1150px; margin: 10px auto 10px auto; padding: 0 18px; }
        .search-row .search-wrap{ flex:1; margin:0; }
        .search-row .search-input{ width:100%; }
        .note{ text-align:center; color:#475569; margin:10px 0 0; }
        /* Batasi lebar tabel tanpa mengurangi lebar kolom pencarian */
        .admin-katalog .katalog-table-wrap{ max-width:1150px; margin:0 auto; }
    </style>
    <script>
        function confirmAndSubmit(input){
            const file = input.files && input.files[0];
            if(!file){ return; }
            const over2MB = file.size > (2 * 1024 * 1024);
            let msg = 'Unggah file katalog "' + file.name + '" ('+Math.round(file.size/1024)+' KB)?';
            if(over2MB){ msg += '\nUkuran > 2MB, proses bisa memakan waktu. Lanjutkan?'; }
            if(confirm(msg)){
                input.form.submit();
            } else {
                input.value = '';
            }
        }
    </script>
    </head>
<body class="admin-katalog">
    <nav class="header-menu">
        <a href="/admin/buku-tamu">Admin Buku Pengunjung</a>
        <a href="/admin/katalog" class="active">Admin Katalog</a>
    </nav>
    <div class="container-center" style="margin: 24px auto 120px auto;">
        <h1 class="section-title">Pratinjau Katalog Buku</h1>
        @if (session('status'))
            <div class="notice success">{{ session('status') }}</div>
        @endif
        @if ($errors->has('file'))
            <div class="notice error">{{ $errors->first('file') }}</div>
        @endif

        <div class="search-row">
            <form class="katalog-search" method="GET" action="/admin/katalog" style="flex:1;">
                <div class="search-wrap">
                    <input type="text" id="searchInput" name="q" value="{{ request('q') }}" class="search-input" placeholder="Cari judul, pengarang, penerbit, tahun, kode...">
                </div>
            </form>
            <form action="/admin/katalog/upload" method="POST" enctype="multipart/form-data" style="display:inline-block;">
                @csrf
                <label for="uploadKatalog" class="upload-label">Perbarui Katalog</label>
                <input type="file" id="uploadKatalog" name="file" accept=".xlsx,.xls" style="display:none" onchange="confirmAndSubmit(this)">
            </form>
        </div>
        @if(!$fileExists)
            <p class="note">Belum ada file katalog.xlsx di folder public. Silakan unggah terlebih dahulu.</p>
        @endif
        <div class="katalog-table-wrap container-center card-table">
            <table class="katalog-table">
                <thead>
                    <tr>
                        <th>No</th>
                        @foreach($header as $col)
                            <th>{{ $col }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $i => $row)
                        <tr>
                            <td>{{ $rows->firstItem() + $i }}</td>
                            @foreach($row as $cell)
                                <td>{{ $cell }}</td>
                            @endforeach
                        </tr>
                    @empty
                        <tr><td colspan="{{ count($header)+1 }}" style="text-align:center; padding:18px 0; color:#64748b;">Tidak ada data untuk ditampilkan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="container-center mt-12">
            <div class="pagination">
                @if ($rows->currentPage() > 1)
                    <a href="{{ $rows->previousPageUrl() }}">« Prev</a>
                @endif
                @for($p=1;$p<=$rows->lastPage();$p++)
                    @if($p == $rows->currentPage())
                        <span class="active">{{ $p }}</span>
                    @else
                        <a href="{{ $rows->url($p) }}">{{ $p }}</a>
                    @endif
                @endfor
                @if ($rows->currentPage() < $rows->lastPage())
                    <a href="{{ $rows->nextPageUrl() }}">Next »</a>
                @endif
            </div>
        </div>
    </div>
    <footer class="footer">&copy; 2025 Dharma Wanita Persatuan Dinas Pekerjaan Umum Sumber Daya Air Provinsi Jawa Timur</footer>
</body>
</html>