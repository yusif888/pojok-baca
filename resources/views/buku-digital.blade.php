<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Buku Digital | Pojok Baca PUSPA</title>
    <link rel="stylesheet" href="{{ asset('css/custom.css?v=1762993010') }}">
</head>
<body>
    <div class="page-bg">
        <nav class="header-menu">
            <a href="/">Halaman Utama</a>
            @if(session('is_admin', false))
                <a href="/beranda">Beranda</a>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-digital').submit();">Logout</a>
                <form id="logout-form-digital" action="/logout" method="POST" style="display:none;">
                    @csrf
                </form>
            @endif
        </nav>
        <div class="content">
            <div class="card">
                <h1>Buku Digital</h1>
                <p>Konten buku digital akan tersedia di sini.</p>
                <div class="mt-20" style="text-align:center">
                    <img src="{{ asset('images/petunjuk1.jpg') }}" alt="Petunjuk" style="max-width:100%;height:auto;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,0.12)" />
                </div>
            </div>
        </div>
    </div>
    <footer class="footer">&copy; 2025 Dharma Wanita Persatuan Dinas Pekerjaan Umum Sumber Daya Air Provinsi Jawa Timur</footer>
    
    <script>
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
                var form = document.getElementById('logout-form-digital');
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
</body>
</html>
