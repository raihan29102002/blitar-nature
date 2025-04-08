# 🌿 Blitar Nature Explore

**Blitar Nature Explore** adalah aplikasi Sistem Informasi untuk pengelolaan dan promosi destinasi wisata alam di Kabupaten Blitar. Aplikasi ini memudahkan wisatawan untuk menemukan tempat wisata alam, melihat informasi lengkap, memberikan rating, dan melihat rekomendasi wisata berdasarkan lokasi maupun rating tertinggi.

## 🚀 Fitur Utama

- 🗺️ Peta interaktif lokasi wisata (terintegrasi Google Maps)
- 🌄 Informasi lengkap setiap wisata (harga, fasilitas, jam buka)
- ⭐ Sistem rating dari pengunjung
- 🎥 Galeri foto dan video pendek
- 🤖 Rekomendasi wisata berdasarkan lokasi dan rating
- 📊 Statistik kunjungan untuk admin
- 👥 Role: Wisatawan dan Admin (Dinas Pariwisata)

## 🛠️ Teknologi

- **Framework**: Laravel + Laravel Livewire
- **Frontend**: Flowbite + Tailwind CSS
- **Authentication**: Laravel Breeze
- **Database**: MySQL
- **Map Integration**: Google Maps API

## 📦 Instalasi

1. Clone repository:

```bash
git clone https://github.com/raihan29102002/blitar-nature.git
cd blitar-nature
Install dependency:
composer install
npm install && npm run dev
Copy file .env:
cp .env.example .env
Generate app key:
php artisan key:generate
Setup database dan migrasi:
php artisan migrate --seed
Jalankan server:
php artisan serve
🙌 Kontribusi
Pull request dan issue sangat diterima! Yuk bantu kembangkan sistem ini untuk pariwisata Blitar yang lebih baik 💚