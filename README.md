# ThesisTrack 🎓  
Aplikasi Manajemen Tugas Akhir Berbasis Laravel 11

## 📸 Tampilan Dashboard  
![Dashboard ThesisTrack](path/to/dashboard-image.png) <!-- Ganti dengan path gambar -->

## 🧑‍💻 Informasi Pengembang
- **Nama**: A. Rahmat Rafgali Raja  
- **NIM**: D02235233  
- **Mata Kuliah**: Framework Web Based  
- **Tahun**: 2025  

## 📝 Deskripsi  
**ThesisTrack** adalah aplikasi web berbasis Laravel 11 yang dirancang untuk memudahkan proses manajemen tugas akhir di lingkungan akademik. Aplikasi ini mendukung interaksi dan koordinasi antara **mahasiswa**, **dosen**, dan **admin** dalam pengelolaan tugas akhir.

---

## 👥 Role dan Fitur

### 🧑 Mahasiswa
- Mengelola tugas akhir (pengajuan, revisi, unggah dokumen)
- Melihat status persetujuan tugas akhir
- Mengakses catatan bimbingan dari dosen

### 👨‍🏫 Dosen
- Menyetujui atau menolak tugas akhir
- Memberikan catatan bimbingan

### 🛠️ Admin
- Mengelola data pengguna (mahasiswa dan dosen)
- Memantau status tugas akhir secara keseluruhan
- Mengatur hak akses dan peran pengguna

---

## 🚀 Fitur Utama
- Dashboard interaktif (Bootstrap 5, Tailwind CSS, AdminLTE)
- Sistem bimbingan online
- Persetujuan tugas akhir dengan catatan dosen
- Sistem login dan logout terintegrasi

---

## 🗄️ Struktur Tabel Database

### Tabel: `users`
| Nama Field       | Tipe Data     | Keterangan                            |
|------------------|---------------|----------------------------------------|
| id               | bigint        | Primary key, auto increment            |
| nama             | varchar(255)  | Nama pengguna                          |
| email            | varchar(255)  | Email pengguna, unik                   |
| password         | varchar(255)  | Password pengguna (hashed)            |
| role             | enum          | admin, dosen, mahasiswa                |
| remember_token   | varchar(100)  | Token untuk fitur remember me         |
| created_at       | timestamp     | Timestamp pembuatan data              |
| updated_at       | timestamp     | Timestamp pembaruan data              |

### Tabel: `mahasiswa`
| Nama Field  | Tipe Data     | Keterangan                                      |
|-------------|---------------|--------------------------------------------------|
| id          | bigint        | Primary key, auto increment                     |
| user_id     | bigint        | Foreign key ke `users`, on delete cascade       |
| nim         | varchar(255)  | Nomor Induk Mahasiswa, unik                     |
| angkatan    | varchar(255)  | Tahun angkatan mahasiswa                        |
| created_at  | timestamp     | Timestamp pembuatan data                        |
| updated_at  | timestamp     | Timestamp pembaruan data                        |

### Tabel: `dosen`
| Nama Field      | Tipe Data     | Keterangan                                    |
|------------------|---------------|-----------------------------------------------|
| id               | bigint        | Primary key, auto increment                   |
| user_id          | bigint        | Foreign key ke `users`, on delete cascade     |
| nip              | varchar(255)  | Nomor Induk Pegawai, unik                     |
| bidang_keahlian  | varchar(255)  | Bidang keahlian dosen                         |
| created_at       | timestamp     | Timestamp pembuatan data                      |
| updated_at       | timestamp     | Timestamp pembaruan data                      |

### Tabel: `tugas_akhirs`
| Nama Field     | Tipe Data     | Keterangan                                                  |
|----------------|---------------|--------------------------------------------------------------|
| id             | bigint        | Primary key, auto increment                                 |
| mahasiswa_id   | bigint        | FK ke `mahasiswa`, on delete cascade                        |
| dosen_id       | bigint        | FK ke `dosen`, nullable, on delete set null                 |
| judul          | varchar(255)  | Judul tugas akhir                                           |
| deskripsi      | text          | Deskripsi tugas akhir                                       |
| file_path      | varchar(255)  | Path file tugas akhir, nullable                             |
| status         | enum          | Status: pending, approved, rejected. Default: pending       |
| catatan        | text          | Catatan dari dosen, nullable                                |
| created_at     | timestamp     | Timestamp pembuatan data                                    |
| updated_at     | timestamp     | Timestamp pembaruan data                                    |

### Tabel: `bimbingan`
| Nama Field       | Tipe Data     | Keterangan                                      |
|------------------|---------------|--------------------------------------------------|
| id               | bigint        | Primary key, auto increment                     |
| tugas_akhir_id   | bigint        | FK ke `tugas_akhirs`, on delete cascade         |
| catatan          | text          | Catatan bimbingan dari dosen                    |
| tanggal          | date          | Tanggal sesi bimbingan                          |
| created_at       | timestamp     | Timestamp pembuatan data                        |
| updated_at       | timestamp     | Timestamp pembaruan data                        |

### Tabel: `password_reset_tokens`
| Nama Field  | Tipe Data     | Keterangan                              |
|-------------|---------------|------------------------------------------|
| email       | varchar(255)  | Primary key, email pengguna              |
| token       | varchar(255)  | Token untuk reset password               |
| created_at  | timestamp     | Timestamp pembuatan token (nullable)     |

---

## 🔗 Relasi Antar Tabel

### One-to-One
- `users` ➝ `mahasiswa`: satu pengguna hanya bisa menjadi satu mahasiswa
- `users` ➝ `dosen`: satu pengguna hanya bisa menjadi satu dosen

### One-to-Many
- `mahasiswa` ➝ `tugas_akhirs`: satu mahasiswa bisa punya banyak tugas akhir
- `dosen` ➝ `tugas_akhirs`: satu dosen bisa membimbing banyak tugas akhir
- `tugas_akhirs` ➝ `bimbingan`: satu tugas akhir memiliki banyak catatan bimbingan

---

## 🛠️ Teknologi yang Digunakan
- Laravel 11
- Laravel Breeze
- Bootstrap 5
- Tailwind CSS
- AdminLTE
- MySQL

---