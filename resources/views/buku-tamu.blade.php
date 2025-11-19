<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Daftar Buku Pengunjung - Pojok Baca</title>
    <link rel="stylesheet" href="{{ asset('css/custom.css?v=1762993010') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="m-0">
    <nav class="header-menu">
        <a href="/">Halaman Utama</a>
        <a href="/beranda">Beranda</a>
        <a href="/buku-tamu" class="active">Buku Pengunjung</a>
        @if($isAdmin)
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-tamu').submit();">Logout</a>
            <form id="logout-form-tamu" action="/logout" method="POST" style="display:none;">
                @csrf
            </form>
        @endif
    </nav>
    <main class="buku-tamu-bg">
        <div class="container-center mt-40">
            <h2 class="section-title">Daftar Pengunjung Pojok Baca</h2>
            
            @if($isAdmin)
            <!-- Filter dan Info untuk Admin -->
            <div class="admin-controls" style="background:#fff; padding:20px; border-radius:12px; margin-bottom:20px; box-shadow:0 2px 12px rgba(0,0,0,0.08);">
                <div style="margin-bottom:15px;">
                    <strong>Menampilkan {{ $tamus->firstItem() }} - {{ $tamus->lastItem() }} dari {{ $tamus->total() }} tamu</strong>
                    @if($month || $year)
                        <span style="color:#666; font-size:0.9em;">
                            (Filter: 
                            @if($month) Bulan {{ $month }} @endif
                            @if($year) Tahun {{ $year }} @endif
                            )
                        </span>
                    @endif
                </div>
                <form method="GET" action="/buku-tamu" style="display:flex; gap:12px; align-items:end; flex-wrap:wrap;">
                    <div>
                        <label style="display:block; margin-bottom:5px; font-weight:600; font-size:0.9em;">Bulan</label>
                        <select name="month" style="padding:8px 12px; border:1.5px solid #e0ecff; border-radius:8px; font-size:1em;">
                            <option value="">Semua</option>
                            @for($m=1;$m<=12;$m++)
                                <option value="{{ $m }}" {{ (string)$m === (string)$month ? 'selected' : '' }}>{{ $m }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label style="display:block; margin-bottom:5px; font-weight:600; font-size:0.9em;">Tahun</label>
                        <select name="year" style="padding:8px 12px; border:1.5px solid #e0ecff; border-radius:8px; font-size:1em;">
                            <option value="">Semua</option>
                            @foreach($years as $y)
                                <option value="{{ $y }}" {{ (string)$y === (string)$year ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display:block; margin-bottom:5px; font-weight:600; font-size:0.9em;">Per Halaman</label>
                        <select name="per_page" style="padding:8px 12px; border:1.5px solid #e0ecff; border-radius:8px; font-size:1em;">
                            @foreach($allowedPerPage as $pp)
                                <option value="{{ $pp }}" {{ (int)$pp === (int)$perPage ? 'selected' : '' }}>{{ $pp }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" style="background:#2ec4b6; color:#fff; border:none; padding:10px 24px; border-radius:8px; font-weight:600; cursor:pointer; box-shadow:0 2px 8px rgba(44,200,180,0.2);">Terapkan Filter</button>
                    <button type="button" onclick="window.print()" style="background:linear-gradient(90deg,#ffb347 0%,#6a82fb 100%); color:#fff; border:none; padding:10px 24px; border-radius:8px; font-weight:600; cursor:pointer; box-shadow:0 2px 8px rgba(0,0,0,0.15);">🖨️ Cetak</button>
                    @if($month || $year)
                        <a href="/buku-tamu" style="background:#e0e0e0; color:#333; text-decoration:none; padding:10px 20px; border-radius:8px; font-weight:600;">Reset</a>
                    @endif
                </form>
            </div>
            @endif
            
            <div class="card-table">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Tanggal</th>
                            <th>Unit</th>
                            <th>Telepon</th>
                            <th>Jenis Buku</th>
                            <th>Tanggal Kunjungan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tamus as $i => $tamu)
                            <tr>
                                <td>{{ method_exists($tamus,'firstItem') ? $tamus->firstItem() + $i : ($i+1) }}</td>
                                <td>
                                    @if($isAdmin)
                                        {{ $tamu->nama }}
                                    @else
                                        @php
                                            $nama = trim($tamu->nama ?? '');
                                            $maskedName = $nama === '' ? '-' : mb_substr($nama, 0, 3, 'UTF-8').'**';
                                        @endphp
                                        {{ $maskedName }}
                                    @endif
                                </td>
                                <td>{{ $tamu->tanggal }}</td>
                                <td>{{ $tamu->unit }}</td>
                                <td>
                                    @if($isAdmin)
                                        {{ $tamu->telepon }}
                                    @else
                                        @php
                                            $tel = (string)($tamu->telepon ?? '');
                                            if (strlen($tel) >= 7) {
                                                $maskedPhone = substr($tel,0,4).'***'.substr($tel,-3);
                                            } else {
                                                $maskedPhone = $tel !== '' ? $tel : '-';
                                            }
                                        @endphp
                                        {{ $maskedPhone }}
                                    @endif
                                </td>
                                <td>{{ $tamu->jenis_buku }}</td>
                                <td>{{ $tamu->tanggal_kunjungan ? \Carbon\Carbon::parse($tamu->tanggal_kunjungan)->timezone('Asia/Jakarta')->format('d-m-Y H:i') : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="container-center mt-12">
                @if(method_exists($tamus,'lastPage'))
                    <div class="pagination">
                        @if ($tamus->currentPage() > 1)
                            <a href="{{ $tamus->previousPageUrl() }}">« Prev</a>
                        @endif
                        @for($p=1;$p<=$tamus->lastPage();$p++)
                            @if($p == $tamus->currentPage())
                                <span class="active">{{ $p }}</span>
                            @else
                                <a href="{{ $tamus->url($p) }}">{{ $p }}</a>
                            @endif
                        @endfor
                        @if ($tamus->currentPage() < $tamus->lastPage())
                            <a href="{{ $tamus->nextPageUrl() }}">Next »</a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </main>
    <footer class="footer">&copy; 2025 Dharma Wanita Persatuan Dinas Pekerjaan Umum Sumber Daya Air Provinsi Jawa Timur</footer>
    
    <script>
    // Auto redirect/logout setelah 60 detik tidak ada aktivitas
    let idleTimer;
    const idleTimeout = 60000; // 60 detik
    const isAdmin = {{ $isAdmin ? 'true' : 'false' }};

    function resetIdleTimer() {
        clearTimeout(idleTimer);
        idleTimer = setTimeout(function() {
            if(isAdmin) {
                // Admin: logout dulu baru redirect
                document.getElementById('logout-form-tamu').submit();
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
</body>
</html>
