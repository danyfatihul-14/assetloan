# Asset Loan

REST API dan web app sederhana untuk mencatat asset dan peminjamannya. Dibuat sebagai technical task seleksi Maganghub 2026 Batch 3, PT Akui Bird Nest Indonesia.

Yang bisa dilakukan: CRUD asset, mencatat peminjaman, mengembalikan asset, serta cari dan filter berdasarkan kode, nama, atau status.

Beberapa aturan yang saya terapkan:

- Asset yang sedang dipinjam atau rusak tidak bisa dipinjam lagi.
- Asset yang punya riwayat peminjaman tidak bisa dihapus.
- Proses pinjam dan kembali dibungkus database transaction supaya status asset dan data peminjaman tidak selisih.

## Stack

Laravel 13 (PHP 8.3+), MySQL, Blade, JavaScript, Vite, dan PHPUnit untuk test.

## Menjalankan di lokal

Butuh PHP 8.3+, Composer, Node.js, dan MySQL.

```bash
git clone https://github.com/USERNAME/assetloan.git
cd assetloan
composer install
npm install
cp .env.example .env      # Windows: copy .env.example .env
php artisan key:generate
```

Buat database `asset_loan` di MySQL, lalu cek bagian ini di `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=asset_loan
DB_USERNAME=root
DB_PASSWORD=
```

Setelah itu migrate dan jalankan server (Vite di terminal terpisah):

```bash
php artisan migrate
php artisan serve
npm run dev
```

Buka http://127.0.0.1:8000. Halaman asset ada di `/assets` dan halaman peminjaman di `/loans`.

## API

Base URL: `http://127.0.0.1:8000/api`

| Method | Endpoint | Fungsi |
|---|---|---|
| GET | `/assets` | Daftar asset (`?q=laptop`, `?status=tersedia`) |
| POST | `/assets` | Tambah asset |
| GET | `/assets/{id}` | Detail asset |
| PUT | `/assets/{id}` | Ubah asset |
| DELETE | `/assets/{id}` | Hapus asset |
| GET | `/loans` | Daftar peminjaman (`?status=dipinjam`) |
| POST | `/loans` | Catat peminjaman |
| GET | `/loans/{id}` | Detail peminjaman |
| PATCH | `/loans/{id}/return` | Kembalikan asset |

Contoh tambah asset (`POST /api/assets`):

```json
{
  "kode": "LPT-001",
  "nama": "Laptop Lenovo",
  "kategori": "Elektronik",
  "kondisi": "baik"
}
```

Contoh catat peminjaman (`POST /api/loans`):

```json
{
  "asset_id": 1,
  "peminjam": "Budi Santoso",
  "keperluan": "Presentasi",
  "tgl_rencana_kembali": "2030-12-31"
}
```

Mengembalikan asset cukup `PATCH /api/loans/1/return` tanpa body.

## Test

```bash
php artisan test
```

File `.env` tidak ikut di-commit, pakai `.env.example` sebagai acuan.

Dany