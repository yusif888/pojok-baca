# SETUP CEPAT - Pojok Baca PUSPA

## Langkah-langkah Setup:

### 1. Buat Database
```sql
CREATE DATABASE pojok_baca;
```

### 2. Install Dependencies
```bash
cd c:\laragon\www\pojok-baca
composer install
npm install
```

### 3. Setup Database
```bash
php artisan migrate
php artisan db:seed
```

### 4. Build Assets
```bash
npm run build
```

### 5. Jalankan Aplikasi
```bash
php artisan serve
```

Buka: http://localhost:8000

## Login Admin
- URL: http://localhost:8000/login
- Username: `admin`
- Password: `admin123`

## Catatan Penting

1. **PHP Version**: Pastikan menggunakan PHP 8.0 atau 8.1 (bukan 8.2+)
2. **Database**: Aplikasi ini menggunakan MySQL (bukan SQLite seperti default Laravel 12)
3. **Katalog**: Upload file Excel katalog buku di halaman Admin Katalog
4. **Assets**: Semua gambar dan CSS sudah disalin dari aplikasi pojokbaca asli

## Perbedaan dari Aplikasi Asli (pojokbaca)

Aplikasi **pojok-baca** ini adalah salinan persis dari **pojokbaca** dengan perbedaan:

- ✅ PHP Version: **8.0** (asli: 8.2)
- ✅ Laravel Version: **9** (asli: 12)
- ✅ Database: **pojok_baca** (asli: pojokbaca)
- ✅ Dependencies disesuaikan untuk kompatibilitas PHP 8.0 & Laravel 9
- ✅ Struktur database, desain, fitur, dan views: **SAMA PERSIS**

## Troubleshooting

**Jika ada error saat `composer install`:**
- Pastikan PHP version adalah 8.0 atau 8.1
- Cek dengan: `php -v`
- Di Laragon, bisa ganti PHP version dari menu Laragon

**Jika assets tidak muncul:**
```bash
npm run build
php artisan config:clear
php artisan cache:clear
```

**Jika error database connection:**
- Pastikan MySQL berjalan
- Cek konfigurasi di file `.env`
- Pastikan database `pojok_baca` sudah dibuat
