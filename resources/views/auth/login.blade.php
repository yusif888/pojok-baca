<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Login Admin - Pojok Baca</title>
    <link rel="stylesheet" href="{{ asset('css/custom.css?v=1762993010') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="login-bg">
        <form method="POST" action="/login" class="login-form">
            @csrf
            <h2>Login Admin</h2>
            @if($errors->any())
                <div class="error">{{ $errors->first() }}</div>
            @endif
            <div class="mb-16">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required autofocus>
            </div>
            <div class="mb-24">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit">Masuk</button>
            <div class="back-link"><a href="/buku-tamu">Kembali ke Buku Pengunjung</a></div>
        </form>
    </div>
</body>
</html>
<footer class="footer">&copy; 2025 Dharma Wanita Persatuan Dinas Pekerjaan Umum Sumber Daya Air Provinsi Jawa Timur</footer>
