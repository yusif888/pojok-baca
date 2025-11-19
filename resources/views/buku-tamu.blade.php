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
</body>
</html>
