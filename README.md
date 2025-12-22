# 📊 Aplikasi Pencatatan Keuangan Personal

Aplikasi web untuk mencatat dan mengelola transaksi keuangan personal dengan fitur dashboard interaktif, manajemen kategori, dan visualisasi data.

## 🎯 Fitur Utama

### 1. **Dashboard Home**
- Statistik ringkas: Total transaksi, pemasukan, pengeluaran, dan sisa saldo
- Kartu info-box dengan icon dan warna yang membedakan jenis transaksi
- Grafik donut untuk visualisasi pengeluaran per kategori (top 3)
- Tab kategori untuk melihat ringkas pengeluaran
- Riwayat transaksi terbaru (5 transaksi terakhir)
- Navbar fixed untuk navigasi yang mudah

### 2. **Manajemen Kategori**
- Daftar kategori dalam tabel responsif
- **Create**: Tambah kategori baru via modal (bukan halaman terpisah)
- **Edit**: Edit nama kategori via modal dengan live update tabel
- **Delete**: Hapus kategori individual dengan konfirmasi
- **Bulk Add**: Tambah kategori default dengan preview duplikat
- **Clear All**: Hapus semua kategori sekaligus dengan konfirmasi

#### Modal Kategori
- **Create Modal**: Form dinamis untuk tambah multiple kategori, bisa add/remove input
- **Edit Modal**: Form sederhana untuk edit nama kategori
- **Responsive**: Scrollable body jika konten panjang, footer sticky untuk tombol

### 3. **Manajemen Transaksi**
- Daftar transaksi dalam tabel responsif dengan kolom: tanggal, nama, kategori, jenis, qty, nominal, total
- **Create**: Tambah transaksi baru (redirect ke halaman create form)
- **Edit**: Edit transaksi (redirect ke halaman edit form)
- **Delete**: Hapus transaksi dengan konfirmasi
- Statistik transaksi: Total transaksi, pemasukan, pengeluaran, sisa saldo
- Grafik tren bulanan: Visualisasi pemasukan & pengeluaran per bulan

### 4. **Sistem Perhitungan**
- `nominal`: Harga satuan per item (decimal)
- `qty`: Jumlah item (integer, default 1)
- `total_nominal`: `qty × nominal` (decimal, sudah dikalikan)
- Dashboard menggunakan `sum('total_nominal')` untuk statistik akurat

## 🛠️ Tech Stack

- **Framework**: Laravel 12.43.1
- **Template**: AdminLTE 3 (Almasaeed2010/adminlte)
- **UI**: Bootstrap 5, FontAwesome 5
- **Database**: SQLite/MySQL (tb_users, tb_kategori, tb_transaksi)
- **Charting**: Chart.js 3 (grafik donut & tren)
- **Frontend**: Blade templating, AJAX (fetch API), jQuery Bootstrap

## 📁 Struktur Project

```
resources/views/
├── layouts/
│   └── app.blade.php           # Layout utama AdminLTE
├── home.blade.php              # Dashboard home
├── kategori/
│   ├── index.blade.php         # Daftar kategori
│   ├── create.blade.php        # Form create kategori (jika pakai halaman)
│   └── edit.blade.php          # Form edit kategori (jika pakai halaman)
├── Transaksi/
│   ├── index.blade.php         # Daftar transaksi
│   ├── create.blade.php        # Form create transaksi
│   └── edit.blade.php          # Form edit transaksi
└── partials/
    ├── history-table.blade.php # Komponen tabel history
    └── modals/
        ├── create-kategori.blade.php  # Modal create kategori
        └── edit-kategori.blade.php    # Modal edit kategori

app/Http/Controllers/
├── HomeController.php          # Logika dashboard
├── KategoriController.php      # CRUD kategori
├── TransaksiController.php     # CRUD transaksi
└── UserController.php          # User management

app/Models/
├── User.php
├── Kategori.php
└── Transaksi.php

database/
└── migrations/
    ├── *_create_users_table.php
    ├── *_create_kategoris_table.php
    └── *_create_transaksis_table.php
```

## 🗄️ Database Schema

### tb_users
```php
id, name, email, password, email_verified_at, created_at, updated_at
```

### tb_kategori
```php
id_kategori (PK), id_user (FK), nama_kategori, created_at, updated_at
```

### tb_transaksi
```php
id_transaksi (PK), id_user (FK), tanggal_transaksi (date), 
nama_transaksi, id_kategori (FK), jenis_transaksi (Pemasukan/Pengeluaran), 
qty (integer, default 1), nominal (decimal), 
total_nominal (decimal = qty × nominal), created_at, updated_at
```

## 🚀 Cara Menggunakan

### Setup Awal
```bash
# Clone atau akses project
cd c:\xampp\htdocs\project_2025\pencatatan_keuangan

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate

# Jalankan server
php artisan serve
```

### Akses Aplikasi
- URL: `http://127.0.0.1:8000`
- Login dengan akun yang sudah terdaftar
- Atau register akun baru

### Fitur Dashboard
1. **Buka Home**: Lihat ringkas statistik, grafik, dan history
2. **Buka Kategori**: Kelola kategori transaksi
   - Klik "Tambah Kategori" → Modal muncul → Isi nama → Submit
   - Klik "Tambah Default" → Preview duplikat → Confirm add
   - Klik "Edit" → Modal edit → Ubah nama → Submit → Auto update tabel
   - Klik "Hapus" → Konfirmasi → Kategori dihapus
3. **Buka Transaksi**: Kelola transaksi
   - Klik "Tambah Transaksi" → Ke halaman form → Isi data → Submit
   - Klik "Edit" → Ke halaman form edit → Ubah data → Submit
   - Klik "Hapus" → Konfirmasi → Transaksi dihapus
   - Lihat grafik tren bulanan di halaman

## 📖 Panduan Penggunaan Detail

### Dashboard Keuangan
Setelah berhasil login, sistem secara otomatis akan menampilkan halaman Dashboard Keuangan.

Pada bagian atas halaman terdapat ringkasan informasi keuangan berupa:
- **Pemasukan**: yang menampilkan total seluruh transaksi pemasukan.
- **Pengeluaran**: yang menampilkan total seluruh transaksi pengeluaran.
- **Sisa Saldo**: yang merupakan hasil pengurangan antara total pemasukan dan total pengeluaran.
- **Total Transaksi**: yang menunjukkan jumlah seluruh transaksi yang telah dicatat.

Pengguna dapat melihat grafik Total Pemasukan vs Pengeluaran dalam bentuk diagram lingkaran untuk membandingkan proporsi pemasukan dan pengeluaran secara visual.

Pada bagian Daftar Kategori, pengguna dapat melihat kategori transaksi yang tersedia, baik kategori pemasukan maupun pengeluaran, beserta total nominalnya.

Pada bagian Transaksi Terbaru, sistem menampilkan daftar transaksi terakhir yang telah dicatat, yang meliputi:
- Nama transaksi
- Kategori transaksi
- Jenis transaksi (Pemasukan atau Pengeluaran)
- Nominal transaksi

Menu navigasi di sebelah kiri dapat digunakan untuk berpindah ke halaman lain seperti Beranda, Kategori, Transaksi, dan Pengguna.

Informasi pada dashboard akan diperbarui secara otomatis setiap kali pengguna menambahkan, mengubah, atau menghapus data transaksi.

### Halaman Kategori
Halaman Kategori digunakan untuk mengelola kategori transaksi yang akan digunakan dalam pencatatan keuangan.

Pengguna dapat melihat daftar kategori yang telah dibuat dalam bentuk tabel, yang mencakup nama kategori dan opsi aksi seperti edit dan hapus.

Untuk menambah kategori baru:
- Klik tombol "Tambah Kategori".
- Isi nama kategori pada form yang muncul.
- Klik "Simpan" untuk menambahkan kategori.

Untuk mengedit kategori:
- Klik tombol "Edit" pada kategori yang ingin diubah.
- Ubah nama kategori pada form edit.
- Klik "Simpan" untuk menyimpan perubahan.

Untuk menghapus kategori:
- Klik tombol "Hapus" pada kategori yang ingin dihapus.
- Konfirmasi penghapusan pada dialog yang muncul.

Pengguna juga dapat menambah kategori default dengan klik tombol "Tambah Default", yang akan menampilkan preview kategori yang akan ditambahkan dan menghindari duplikasi.

### Halaman Transaksi
Halaman Transaksi digunakan untuk mencatat dan mengelola semua transaksi keuangan.

Pengguna dapat melihat daftar transaksi dalam tabel yang mencakup tanggal, nama transaksi, kategori, jenis (Pemasukan/Pengeluaran), jumlah (qty), nominal per item, dan total nominal.

Untuk menambah transaksi baru:
- Klik tombol "Tambah Transaksi".
- Isi form dengan detail transaksi: tanggal, nama, kategori, jenis, qty, dan nominal.
- Klik "Simpan" untuk mencatat transaksi.

Untuk mengedit transaksi:
- Klik tombol "Edit" pada transaksi yang ingin diubah.
- Ubah detail transaksi pada form edit.
- Klik "Simpan" untuk menyimpan perubahan.

Untuk menghapus transaksi:
- Klik tombol "Hapus" pada transaksi yang ingin dihapus.
- Konfirmasi penghapusan pada dialog yang muncul.

Halaman ini juga menampilkan statistik transaksi dan grafik tren bulanan untuk visualisasi pemasukan dan pengeluaran per bulan.

### Halaman Pengguna
Halaman Pengguna digunakan untuk mengelola informasi akun pengguna.

Pengguna dapat melihat dan mengedit profil mereka, termasuk nama dan email.

Untuk mengedit profil:
- Klik menu "Pengguna".
- Ubah informasi yang diperlukan pada form.
- Klik "Simpan" untuk menyimpan perubahan.

Fitur ini memastikan bahwa setiap pengguna hanya dapat mengakses dan mengubah data mereka sendiri.

## 🎨 UI/UX

### Desain
- **Warna Dominan**: Biru (primary), dengan green (success), red (danger), yellow (warning)
- **Responsif**: Mobile-first, optimal di HP dan laptop
- **Icon**: FontAwesome untuk visual yang jelas

### Component Reusable
- **Modal**: Menggunakan Bootstrap modal, scrollable body, sticky footer
- **Card**: AdminLTE info-box untuk statistik
- **Table**: Responsive, hover effect, dropdown action menu
- **Form**: Bootstrap form-control dengan validasi

## 🔐 Keamanan

- **Authentication**: Laravel built-in auth dengan password hashing
- **Authorization**: Setiap user hanya bisa akses data mereka sendiri (via `authorizeOwnership`)
- **CSRF Protection**: Semua form include `@csrf` token
- **Input Validation**: Server-side validation di controller (required, exists, unique, dll)

## 📊 Statistik & Grafik

### Dashboard Home
- **Grafik Donut**: Top 3 kategori dengan pengeluaran terbanyak
- **Tab Kategori**: Ringkas pengeluaran per kategori (top 3)
- **History Table**: Transaksi terbaru dengan sorting desc by created_at

### Halaman Transaksi
- **Grafik Tren Bulanan**: Line chart pemasukan & pengeluaran per bulan
- **Filter**: Bisa kustomisasi sesuai kebutuhan

## 🐛 Troubleshooting

| Error | Solusi |
|-------|--------|
| Modal tidak muncul | Pastikan Bootstrap JS & jQuery loaded, check browser console |
| Grafik tidak render | Buka browser DevTools, cek apakah Chart.js library loaded |
| Update tidak berubah | Refresh page, atau check authorization (`authorizeOwnership`) |
| 403 Forbidden saat edit | Kategori/transaksi bukan milik user, check `id_user` di database |

## 📝 API Endpoints

| Method | URL | Controller | Action |
|--------|-----|------------|--------|
| GET | /home | HomeController | index |
| GET | /kategori | KategoriController | index |
| POST | /kategori | KategoriController | store |
| GET | /kategori/{id} | KategoriController | show |
| PUT | /kategori/{id} | KategoriController | update |
| DELETE | /kategori/{id} | KategoriController | destroy |
| POST | /kategori/preview-defaults | KategoriController | previewDefaults |
| POST | /kategori/clear | KategoriController | clear |
| GET | /transaksi | TransaksiController | index |
| POST | /transaksi | TransaksiController | store |
| GET | /transaksi/{id}/edit | TransaksiController | edit |
| PUT | /transaksi/{id} | TransaksiController | update |
| DELETE | /transaksi/{id} | TransaksiController | destroy |

## 🔄 Alur Singkat

### Menambah Transaksi
```
Home → Tombol "Tambah Transaksi" → Form create
→ Isi: Tanggal, Nama, Kategori, Jenis (Pemasukan/Pengeluaran), Qty, Nominal
→ Submit → Database save total_nominal = qty × nominal
→ Redirect ke transaksi.index dengan success message
```

### Edit Kategori (via Modal)
```
Kategori Index → Klik Edit dropdown → Modal terbuka dengan data
→ Ubah nama kategori → Submit
→ Controller validasi & update → Redirect ke index
→ Tabel terbaru refresh dengan nama baru
```

### Dashboard Flow
```
Home → Statistik dari Transaksi (sum total_nominal)
→ Chart dari kategori top 3
→ History dari 5 transaksi terbaru
→ Real-time calculate: sisaSaldo = pemasukan - pengeluaran
```

## 📈 Fitur Mendatang

- [ ] Export data ke Excel/PDF
- [ ] Filter transaksi by date range
- [ ] Budget management dengan alert
- [ ] Recurring transaction
- [ ] Multi-currency support
- [ ] Dark mode

## 📞 Support

Untuk pertanyaan atau bug report, silakan hubungi atau buat issue di repository.

---

**Last Updated**: December 18, 2025
**Version**: 1.0.0
**Author**: Development Team
