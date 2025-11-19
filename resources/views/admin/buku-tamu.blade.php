<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Admin - Buku Pengunjung Pojok Baca</title>
    <link rel="stylesheet" href="{{ asset('css/custom.css?v=1762993010') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="admin-page">
    <header>
        <nav>
            <a href="/admin/buku-tamu" class="active">Admin Buku Pengunjung</a>
            <a href="/admin/katalog" class="link-katalog">Admin Katalog</a>
        </nav>
    </header>
    <div class="wrap">
    <h1>Data Lengkap Buku Pengunjung</h1>
        @if (session('status'))
            <div class="notice success">{{ session('status') }}</div>
        @endif
        @if ($errors->has('file'))
            <div class="notice error">{{ $errors->first('file') }}</div>
        @endif
        <div class="top-actions">
            <div>
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
            <form method="POST" action="/logout" class="m-0">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
        <form class="filter-bar" method="GET" action="/admin/buku-tamu">
            <div>
                <label>Bulan</label>
                <select name="month">
                    <option value="">Semua</option>
                    @for($m=1;$m<=12;$m++)
                        <option value="{{ $m }}" {{ (string)$m === (string)$month ? 'selected' : '' }}>{{ $m }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label>Tahun</label>
                <select name="year">
                    <option value="">Semua</option>
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ (string)$y === (string)$year ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Per Halaman</label>
                <select name="per_page">
                    @foreach($allowedPerPage as $pp)
                        <option value="{{ $pp }}" {{ (int)$pp === (int)$perPage ? 'selected' : '' }}>{{ $pp }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit">Terapkan</button>
                <button type="button" class="print-btn" onclick="window.print()">Cetak</button>
            </div>
        </form>
        <div class="table-card">
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
                        <tr data-id="{{ $tamu->id }}">
                            <td>{{ $tamus->firstItem() + $i }}</td>
                            <td>{{ $tamu->nama }}</td>
                            <td>{{ $tamu->tanggal }}</td>
                            <td>{{ $tamu->unit }}</td>
                            <td>{{ $tamu->telepon }}</td>
                            <td>{{ $tamu->jenis_buku }}</td>
                            <td class="date-cell">
                                {{ $tamu->tanggal_kunjungan ? \Carbon\Carbon::parse($tamu->tanggal_kunjungan)->timezone('Asia/Jakarta')->format('d-m-Y H:i') : '-' }}
                                <button class="delete-btn" title="Hapus" data-id="{{ $tamu->id }}">×</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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
    </div>
    <footer class="footer">&copy; 2025 Dharma Wanita Persatuan Dinas Pekerjaan Umum Sumber Daya Air Provinsi Jawa Timur</footer>
    <script>
        function hapusTamu(e, id){
            e.preventDefault();
            if(!confirm('Hapus entri ini?')) return;
            fetch('/admin/buku-tamu/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            }).then(r=>r.json()).then(data=>{
                if(data.ok){
                    const row = document.querySelector('tr[data-id="'+id+'"]');
                    if(row) row.remove();
                } else {
                    alert('Gagal menghapus');
                }
            }).catch(()=> alert('Terjadi kesalahan jaringan'));
        }
        document.addEventListener('click', function(ev){
            if(ev.target.classList.contains('delete-btn')){
                const id = ev.target.getAttribute('data-id');
                hapusTamu(ev, id);
            }
        });
    </script>
</body>
</html>
