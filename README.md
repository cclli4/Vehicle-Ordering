## System Requirements

- PHP Version: 8.2.12
- Database: 10.4.32-MariaDB
- Laravel Framework: 11.36.1

## Default User Credentials
```
Admin User:
Email: admin@example.com
Password: admin123

Approver Level 1:
Email: approver1@example.com
Password: approver123

Approver Level 2:
Email: approver2@example.com
Password: approver123
```

## Installation Steps

1. Clone repository
2. Run `composer install`
3. Copy `.env.example` to `.env`
4. Configure database settings
5. Run migrations: `php artisan migrate`
6. Run seeders: `php artisan db:seed`
7. Generate key: `php artisan key:generate`
8. Start server: `php artisan serve`


## Panduan Penggunaan Aplikasi: Sistem Manajemen Booking Kendaraan
Berikut adalah langkah-langkah untuk menggunakan aplikasi manajemen booking kendaraan

**Memulai Aplikasi**
Login Pengguna:
Buka aplikasi dan masukkan username serta password.
Klik tombol Login.

**Memeriksa Hak Akses**
Setelah login, sistem akan memeriksa peran pengguna (role):
Admin: Melakukan proses booking kendaraan.
Approval: Menyetujui atau menolak permintaan booking.

## Panduan untuk Admin
**Membuat Booking Kendaraan**
Akses Formulir Booking:
- Jika Anda adalah Admin, pilih menu Pemesanan.
- Klik tambah pemesanan

## Isi Detail Booking:
Lengkapi informasi yang ada pada form pemesanan.
Klik tombol Submit untuk mengajukan booking.
Sistem akan memberikan nomor booking sebagai tanda pengajuan berhasil.

## Panduan untuk Proses Persetujuan
Persetujuan Pertama
Pemeriksaan Pengajuan:
Masuk ke menu Approval dan lihat daftar pengajuan.

Setujui atau Tolak:
Jika pengajuan disetujui, klik Approve.
Jika ditolak, klik Reject dan tambahkan alasan penolakan.

Proses Selanjutnya:
Jika ada tingkatan persetujuan lain, sistem akan meneruskan ke level berikutnya.
Persetujuan Final
Keputusan Akhir:
Jika berada di tahap final, pilih untuk Approve atau Reject.
Jika disetujui, status pengajuan diperbarui menjadi Approved.

Jika Ditolak
Jika pengajuan ditolak pada level mana pun, sistem akan memberi notifikasi ke pemohon dengan status Rejected.

Kendaraan Siap Digunakan:
Setelah disetujui, status kendaraan akan berubah menjadi in_use.

Notifikasi Penolakan:
Jika pengajuan ditolak, sistem akan mengirimkan notifikasi ke pemohon dengan status Rejected.

Tindakan Pemohon:
Pemohon dapat memperbaiki detail booking dan mengajukan ulang jika diperlukan.

Akhir dari Proses
Setelah proses selesai (baik disetujui atau ditolak), pengguna dapat logout dari aplikasi.
Proses selesai dan aplikasi siap digunakan kembali untuk pengajuan berikutnya.

## PDM & ACTIVITY DIAGRAM Files
https://drive.google.com/file/d/1ciWlIVewiihimp76llDIKPnuviWoZAvs/view?usp=drive_link
