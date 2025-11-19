<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Pojok Baca</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/custom.css?v=' . time()) }}">
</head>
<body>
    <div class="main-bg">
                <div class="home-logo-cluster">
                    <img src="{{ asset('images/lgpusda.png') }}" alt="Logo PUSDA" />
                    <img src="{{ asset('images/lgdwp.png') }}" alt="Logo DWP" />
                    <img src="{{ asset('images/lgpuspa1.png') }}" alt="Logo PUSPA" class="logo-puspa" />
                </div>
                <button class="btn-mulai btn-mulai-bawah" id="btnMulai">Mulai Baca</button>
                <!-- Modal Pop Up Buku Pengunjung -->
                <div id="modalBukuTamu" class="modal">
                    <div class="modal-content">
                        <span class="close" id="closeModal">&times;</span>
                        <h3 class="mb-18">Sebelum mulai membaca, isi buku pengunjung terlebih dahulu ya <span class="emoji-xl">😊</span></h3>
                        <button class="btn-mulai" id="btnIsiBukuTamu">Isi Buku Pengunjung</button>
                    </div>
                </div>
                <!-- Modal Form Buku Pengunjung (Akan diisi JS) -->
                <div id="modalFormBukuTamu" class="modal">
                    <div class="modal-content soft-modal">
                        <span class="close" id="closeFormModal">&times;</span>
                        <div class="soft-modal-header">
                            <span class="soft-modal-icon">🚀</span>
                            <h3 class="soft-modal-title">Form Buku Pengunjung</h3>
                        </div>
                        <p class="soft-modal-desc">Isi data di bawah ini untuk mulai membaca</p>
                        <form class="form-buku-tamu soft-form" id="formBukuTamu">
                            <div class="soft-input-group">
                                <span class="soft-input-icon">👤</span>
                                <input type="text" id="nama" name="nama" required autocomplete="off" placeholder="Nama Lengkap" class="soft-input">
                            </div>
                            <div class="soft-input-group">
                                <span class="soft-input-icon">🏢</span>
                                <input type="text" id="unit" name="unit" required placeholder="Dinas../Bidang../Masyarakat Umum" class="soft-input">
                            </div>
                            <div class="soft-input-group">
                                <span class="soft-input-icon">📅</span>
                                <input type="date" id="tanggal" name="tanggal" required class="soft-input" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="soft-input-group">
                                <span class="soft-input-icon">📱</span>
                                <input type="tel" id="telepon" name="telepon" required placeholder="Nomor Telepon" class="soft-input">
                            </div>
                            
                            <button type="submit" class="soft-submit-btn">Kirim 🚀</button>
                        </form>
                    </div>
                </div>
                <footer class="footer">
                    Copyright &copy; 2025 Dharma Wanita Persatuan Dinas Pekerjaan Umum Sumber Daya Air Provinsi Jawa Timur
                </footer>
            </div>
            <script>
            // Modal logic
            const btnMulai = document.getElementById('btnMulai');
            const modalBukuTamu = document.getElementById('modalBukuTamu');
            const closeModal = document.getElementById('closeModal');
            const btnIsiBukuTamu = document.getElementById('btnIsiBukuTamu');
            const modalFormBukuTamu = document.getElementById('modalFormBukuTamu');
            const closeFormModal = document.getElementById('closeFormModal');
            btnMulai.onclick = () => { modalBukuTamu.style.display = 'flex'; };
            closeModal.onclick = () => { modalBukuTamu.style.display = 'none'; };
            btnIsiBukuTamu.onclick = () => {
                modalBukuTamu.style.display = 'none';
                modalFormBukuTamu.style.display = 'flex';
            };
            closeFormModal.onclick = () => { modalFormBukuTamu.style.display = 'none'; };
            // Tutup modal jika klik di luar konten
            window.onclick = function(event) {
                if (event.target === modalBukuTamu) modalBukuTamu.style.display = 'none';
                if (event.target === modalFormBukuTamu) modalFormBukuTamu.style.display = 'none';
            };
            // Form submit (AJAX atau redirect bisa diatur di sini)
            document.getElementById('formBukuTamu').onsubmit = function(e) {
                e.preventDefault();
                const form = e.target;
                const formData = new FormData(form);
                fetch('/buku-tamu', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.redirect) {
                        window.location.href = data.redirect;
                    } else if (data.success) {
                        alert('Terima kasih sudah mengisi buku pengunjung!');
                        modalFormBukuTamu.style.display = 'none';
                    } else {
                        alert('Gagal menyimpan data.');
                    }
                })
                .catch(() => alert('Terjadi kesalahan.'));
            };
            </script>
    </body>
</html>
