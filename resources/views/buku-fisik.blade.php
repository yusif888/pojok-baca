<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Katalog Buku Fisik | Pojok Baca</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&family=Poppins:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body>
    <div class="katalog-bg">
    <div class="katalog-container">
            <nav class="header-menu">
            <a href="/">Halaman Utama</a>
            @if(session('is_admin', false))
                <a href="/beranda">Beranda</a>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-fisik').submit();">Logout</a>
                <form id="logout-form-fisik" action="/logout" method="POST" style="display:none;">
                    @csrf
                </form>
            @endif
            </nav>
            <div class="katalog-info container-center">
                <h2>KATALOG BUKU FISIK</h2>
                <p class="katalog-desc">Silakan mencatat judul buku atau kode buku kemudian cari pada rak yang telah tersedia di area Pojok Baca.</p>
                <p class="katalog-desc" style="margin-top:6px; color:#0f766e;">Jumlah buku saat ini: <strong>{{ number_format($rows->total()) }}</strong></p>
                
                @if(session('is_admin', false))
                <!-- Upload Katalog untuk Admin -->
                <div style="margin-top:20px; padding:15px; background:#fff; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.08);">
                    <form action="/admin/katalog/upload" method="POST" enctype="multipart/form-data" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                        @csrf
                        <label style="font-weight:600; color:#2d3a4a;">📂 Update Katalog:</label>
                        <input type="file" name="file" accept=".xlsx,.xls" required style="padding:8px; border:1.5px solid #e0ecff; border-radius:8px; flex:1; min-width:200px;">
                        <button type="submit" style="background:#2ec4b6; color:#fff; border:none; padding:10px 24px; border-radius:8px; font-weight:600; cursor:pointer; box-shadow:0 2px 8px rgba(44,200,180,0.2);">⬆️ Upload</button>
                    </form>
                    @if(session('status'))
                        <p style="margin-top:10px; padding:8px; background:#d4edda; color:#155724; border-radius:6px; font-size:0.9em;">{{ session('status') }}</p>
                    @endif
                    @if($errors->has('file'))
                        <p style="margin-top:10px; padding:8px; background:#f8d7da; color:#721c24; border-radius:6px; font-size:0.9em;">{{ $errors->first('file') }}</p>
                    @endif
                </div>
                @endif
            </div>
            <form class="katalog-search container-center" method="GET" action="/katalog">
                <div class="search-wrap" style="display:flex; gap:10px; align-items:center;">
                    <input type="text" id="searchInput" name="q" value="{{ request('q') }}" class="search-input" placeholder="Cari judul, pengarang, penerbit, tahun, kode...">
                    <select id="perPageSelect" name="per_page" style="flex:0 0 180px; padding:12px; border-radius:12px; border:1.5px solid #e0ecff;">
                        @foreach(($allowedPerPage ?? [10,20,30,50,100]) as $pp)
                            <option value="{{ $pp }}" {{ (int)($perPage ?? 20) === (int)$pp ? 'selected' : '' }}>{{ $pp }} / halaman</option>
                        @endforeach
                    </select>
                </div>
            </form>
            <div class="katalog-table-wrap container-center card-table">
                <table class="katalog-table" id="katalogTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            @foreach($header as $col)
                                <th>{{ $col }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $i => $row)
                            <tr>
                                <td>{{ $rows->firstItem() + $i }}</td>
                                @foreach($row as $cell)
                                    <td>{{ $cell }}</td>
                                @endforeach
                            </tr>
                        @endforeach
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
    </div>
    <footer class="footer">&copy; 2025 Dharma Wanita Persatuan Dinas Pekerjaan Umum Sumber Daya Air Provinsi Jawa Timur</footer>
    </div>
</body>
<script>
// Jika user datang dari Halaman Utama, hapus history katalog agar tombol back tidak kembali ke katalog
if (document.referrer && document.referrer.endsWith('/')) {
    history.replaceState(null, '', '/');
}
// Debounced server-side search across all data (preserves pagination)
(function(){
    const input = document.getElementById('searchInput');
    if(!input) return;
    let t;
    input.addEventListener('input', function(){
        clearTimeout(t);
        t = setTimeout(()=>{
            const params = new URLSearchParams(window.location.search);
            const q = input.value.trim();
            if(q) params.set('q', q); else params.delete('q');
            params.delete('page'); // reset to page 1 when searching
            window.location.search = params.toString();
        }, 400);
    });
})();

// Per-page selector: update per_page param and reset page
(function(){
    const sel = document.getElementById('perPageSelect');
    if(!sel) return;
    sel.addEventListener('change', function(){
        const params = new URLSearchParams(window.location.search);
        const val = sel.value;
        if(val) params.set('per_page', val); else params.delete('per_page');
        params.delete('page');
        window.location.search = params.toString();
    });
})();

// Jika user klik Halaman Utama, hapus history katalog agar tombol back tidak kembali ke katalog
document.querySelectorAll('.header-menu a[href="/"]').forEach(function(link) {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        window.location.replace('/');
    });
});

// 1. Cegah back button - redirect ke halaman utama jika user menekan back
window.history.pushState(null, '', window.location.href);
window.addEventListener('popstate', function() {
    window.history.pushState(null, '', window.location.href);
    window.location.replace('/');
});

// 2. Auto redirect/logout setelah 60 detik tidak ada aktivitas
let idleTimer;
const idleTimeout = 60000; // 60 detik
const isAdmin = {{ session('is_admin', false) ? 'true' : 'false' }};

function resetIdleTimer() {
    clearTimeout(idleTimer);
    idleTimer = setTimeout(function() {
        if(isAdmin) {
            // Admin: logout dulu baru redirect
            var form = document.getElementById('logout-form-fisik');
            if(form) form.submit();
            else window.location.replace('/');
        } else {
            // User biasa: langsung redirect
            window.location.replace('/');
        }
    }, idleTimeout);
}

// Reset timer saat ada aktivitas
['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'].forEach(function(event) {
    document.addEventListener(event, resetIdleTimer, true);
});

// Mulai timer
resetIdleTimer();
</script>
</html>
