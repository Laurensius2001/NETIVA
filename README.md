Halaman Home Page
![image](https://github.com/user-attachments/assets/fe5cba7c-71bd-43b6-9b8d-248ba1122a9f)

Halaman Login
![image](https://github.com/user-attachments/assets/038b06b4-660b-4f16-92a8-597241b382f6)

Halaman Nakes
![image](https://github.com/user-attachments/assets/bf6ba440-9371-4287-ae19-50c46644f689)

Halaman Dokter
![image](https://github.com/user-attachments/assets/0cf382ec-4728-4b6a-a3c8-2d0094779180)

Halaman Admin Pemerintah
![image](https://github.com/user-attachments/assets/63ade064-7be3-400f-9e99-5022eec358e2)


# NETIVA - Deteksi Dini Kanker Serviks

Aplikasi berbasis web untuk membantu proses deteksi dini kanker serviks, memungkinkan dokter dan admin pemerintahan melakukan verifikasi data pasien serta analisis visual citra medis.

---

## 🔑 Fitur Utama

### 👥 Role Pengguna

1. **Admin Utama**
   - Login
   - Lihat dashboard peta sebaran kanker serviks berdasarkan lokasi pasien
   - Kelola data dokter dan pasien
   - Upload dan kelola citra medis (sebelum, sesudah, AI)
   - Verifikasi status pasien

2. **Dokter**
   - Lihat data diri dan pasien
   - Verifikasi status kanker pasien
   - Anotasi gambar medis (sebelum, sesudah, AI) menggunakan Fabric.js

3. **Admin Pemerintahan**
   - Login akun
   - Lihat dashboard peta sebaran kanker serviks berdasarkan lokasi pasien dan bisa melihat detail nama-nama pasien yg terverifikasi positif berdasarkan lokasi pasien

---

## 🖼️ Fitur Citra Medis

- Upload gambar `citra_sebelum`, `citra_sesudah`, dan `citra_ai`
- Preview gambar 
- Fitur anotasi interaktif (coretan, undo, hapus) menggunakan **Fabric.js**
- Gambar anotasi disimpan dan menimpa file lama

---

## 🗺️ Visualisasi Peta

- Dashboard admin pemerintahan menampilkan peta distribusi kanker serviks
- Data pasien ditampilkan berdasarkan lokasi (contoh: Bandung)

---

## ⚙️ Teknologi yang Digunakan

- Laravel 11
- Bootstrap 5
- Fabric.js (untuk anotasi gambar)
- MySQL via Laragon (dev environment)
- Git & GitHub

---

## 📦 Instalasi

```bash
git clone https://github.com/Laurensius2001/NETIVA.git
cd NETIVA
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve


<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
