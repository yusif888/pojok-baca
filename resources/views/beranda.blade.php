<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Beranda | Pojok Baca PUSPA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css?v=1762993010') }}">
</head>
<body>
    <div class="beranda-bg">
        <nav class="header-menu">
            <a href="/">Halaman Utama</a>
            <a href="/beranda" class="active">Beranda</a>
            <a href="/buku-tamu">Buku Pengunjung</a>
            <a href="/profil">Profil</a>
        </nav>
    <div class="main-menu-wrap">
        <div class="main-menu-title">Jenis Buku Apa Yang Ingin Anda Baca?</div>
            <div class="main-menu-desc">Silakan pilih salah satu jenis buku di bawah ini untuk mulai menjelajah koleksi kami.</div>
            <div class="main-menu-books">
                <div class="book-menu digital" onclick="window.location.href='/buku-digital'">
                    <div class="book-icon">📖</div>
                    <div class="book-label">Buku Digital</div>
                </div>
                <div class="book-menu fisik" onclick="window.location.href='/buku-fisik'">
                    <div class="book-icon">📚</div>
                    <div class="book-label">Buku Fisik</div>
                </div>
            </div>
            <div class="qr-wrap">
                <img src="{{ asset('images/skm_puspa.png') }}" alt="QR Code Survei Kepuasan" />
                <div class="qr-panel">
                    <div class="qr-caption">Kepuasan Anda adalah prioritas kami.<br>Pindai QR code ini dan berikan masukan berharga Anda.</div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer">&copy; 2025 Dharma Wanita Persatuan Dinas Pekerjaan Umum Sumber Daya Air Provinsi Jawa Timur</footer>
</body>
</html>
