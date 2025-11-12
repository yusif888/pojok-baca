# Pojok Baca PUSPA<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>



Aplikasi Pojok Baca untuk Dharma Wanita Persatuan Dinas Pekerjaan Umum Sumber Daya Air Provinsi Jawa Timur.<p align="center">

<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>

## Requirements<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>

<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>

- PHP ^8.0<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>

- Laravel 9</p>

- MySQL

- Composer## About Laravel

- Node.js & NPM

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

## Installation

- [Simple, fast routing engine](https://laravel.com/docs/routing).

### 1. Install Dependencies- [Powerful dependency injection container](https://laravel.com/docs/container).

- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.

```bash- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).

composer install- Database agnostic [schema migrations](https://laravel.com/docs/migrations).

npm install- [Robust background job processing](https://laravel.com/docs/queues).

```- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).



### 2. Setup EnvironmentLaravel is accessible, powerful, and provides tools required for large, robust applications.



File `.env` sudah dikonfigurasi dengan database MySQL. Pastikan database `pojok_baca` sudah dibuat:## Learning Laravel



```sqlLaravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

CREATE DATABASE pojok_baca;

```If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.



Jika perlu, edit file `.env` untuk menyesuaikan konfigurasi database:## Laravel Sponsors



```We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

DB_CONNECTION=mysql

DB_HOST=127.0.0.1### Premium Partners

DB_PORT=3306

DB_DATABASE=pojok_baca- **[Vehikl](https://vehikl.com)**

DB_USERNAME=root- **[Tighten Co.](https://tighten.co)**

DB_PASSWORD=- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**

```- **[64 Robots](https://64robots.com)**

- **[Curotec](https://www.curotec.com/services/technologies/laravel)**

### 3. Generate Application Key (jika belum ada)- **[DevSquad](https://devsquad.com/hire-laravel-developers)**

- **[Redberry](https://redberry.international/laravel-development)**

```bash- **[Active Logic](https://activelogic.com)**

php artisan key:generate

```## Contributing



### 4. Run MigrationsThank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).



```bash## Code of Conduct

php artisan migrate

```In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).



### 5. Seed Database (Create Admin User)## Security Vulnerabilities



```bashIf you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

php artisan db:seed

```## License



**Default Admin Credentials:**The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

- Username: `admin`
- Password: `admin123`

**PENTING:** Ganti password admin setelah login pertama kali!

### 6. Build Assets

```bash
npm run build
```

Untuk development dengan hot reload:
```bash
npm run dev
```

### 7. Run Application

```bash
php artisan serve
```

Aplikasi akan berjalan di: http://localhost:8000

## Struktur Database

### Tabel `buku_tamus`
- id
- nama
- tanggal
- unit
- telepon
- jenis_buku (enum: 'digital', 'fisik')
- tanggal_kunjungan
- created_at
- updated_at

### Tabel `admins`
- id
- username (unique)
- password
- created_at
- updated_at

## Fitur Utama

### Public Features:
1. **Halaman Utama** (`/`) - Landing page dengan form buku pengunjung
2. **Beranda** (`/beranda`) - Pilihan jenis buku (Digital/Fisik)
3. **Katalog Buku Fisik** (`/katalog`, `/buku-fisik`) - Daftar buku dari file Excel dengan pencarian
4. **Buku Digital** (`/buku-digital`) - Placeholder untuk konten digital
5. **Buku Pengunjung** (`/buku-tamu`) - Daftar pengunjung (data tersensor untuk publik)
6. **Profil** (`/profil`) - Informasi tentang Pojok Baca PUSPA

### Admin Features:
1. **Login Admin** (`/login`)
2. **Admin Buku Pengunjung** (`/admin/buku-tamu`) - Lihat semua data lengkap dengan filter dan hapus entri
3. **Admin Katalog** (`/admin/katalog`) - Upload dan preview file katalog Excel

## Upload Katalog Buku

Admin dapat mengupload file Excel (`.xlsx`, `.xls`) melalui halaman Admin Katalog.

Format file Excel:
- Baris pertama: Header (Judul Buku, Pengarang, Penerbit, Tahun, Kode Buku)
- Baris selanjutnya: Data buku

File akan disimpan sebagai `public/katalog.xlsx`.

## Mengubah Password Admin

Untuk mengubah password admin, jalankan di PHP artisan tinker atau buat seeder baru:

```bash
php artisan tinker
```

Kemudian jalankan:
```php
DB::table('admins')->where('username', 'admin')->update(['password' => Hash::make('password_baru_anda')]);
```

## Development

Untuk menjalankan dalam mode development:

```bash
# Terminal 1 - Laravel Server
php artisan serve

# Terminal 2 - Vite Dev Server
npm run dev
```

## Production Deployment

1. Set `APP_ENV=production` dan `APP_DEBUG=false` di `.env`
2. Run `composer install --optimize-autoloader --no-dev`
3. Run `npm run build`
4. Run `php artisan config:cache`
5. Run `php artisan route:cache`
6. Run `php artisan view:cache`
7. Set proper permissions untuk storage dan bootstrap/cache

## Troubleshooting

### Error "Class PhpOffice\PhpSpreadsheet\IOFactory not found"
Pastikan sudah menjalankan `composer install` untuk menginstall dependencies.

### Database Connection Error
Periksa konfigurasi database di file `.env` dan pastikan MySQL berjalan.

### Assets not loading
Jalankan `npm run build` untuk compile assets.

## License

Copyright © 2025 Dharma Wanita Persatuan Dinas Pekerjaan Umum Sumber Daya Air Provinsi Jawa Timur

## Credits

Aplikasi ini merupakan salinan dari pojokbaca dengan adaptasi untuk PHP 8.0 dan Laravel 9.
