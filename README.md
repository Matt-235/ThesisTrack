# 🎓 ThesisTrack  
**Aplikasi Manajemen Tugas Akhir Berbasis Laravel 11**

![ThesisTrack](https://github.com/user-attachments/assets/c968dc9d-2459-4d12-9970-55c1f1d45c04)

---

## 👨‍💻 Informasi Pengembang
- **Nama**: A. Rahmat Rafgali Raja  
- **NIM**: D0223523  
- **Mata Kuliah**: Framework Web Based  
- **Tahun**: 2025  

---

## 📝 Deskripsi  
**ThesisTrack** adalah aplikasi web berbasis Laravel 11 yang dirancang untuk memudahkan manajemen tugas akhir di lingkungan akademik. Aplikasi ini memungkinkan interaksi antara **mahasiswa**, **dosen**, dan **admin** untuk mengelola proses pengajuan, bimbingan, dan persetujuan tugas akhir.

---

## 👥 Role dan Fitur

### 🧑 Mahasiswa
- Mengajukan tugas akhir (judul, deskripsi, dokumen)
- Melihat status persetujuan
- Mengakses riwayat dan catatan bimbingan

### 👨‍🏫 Dosen
- Menyetujui atau menolak pengajuan tugas akhir
- Memberikan catatan bimbingan
- Melihat daftar mahasiswa bimbingan

### 🛠️ Admin
- Mengelola data pengguna (mahasiswa & dosen)
- Mengatur hak akses dan peran
- Memantau progres dan status tugas akhir

---

## 🚀 Fitur Utama
- Dashboard dinamis menggunakan **Bootstrap 5**, **Tailwind CSS**, dan **AdminLTE**
- Sistem bimbingan online
- Persetujuan tugas akhir dengan fitur catatan dosen
- Sistem login/logout terintegrasi dengan Laravel Auth

---

## 🗄️ Struktur Tabel Database

### Tabel: `users`
| Field            | Tipe Data     | Keterangan                              |
|------------------|---------------|------------------------------------------|
| id               | bigint        | Primary key                              |
| nama             | varchar(255)  | Nama pengguna                            |
| email            | varchar(255)  | Email unik                               |
| password         | varchar(255)  | Password terenkripsi                     |
| role             | enum          | Peran: admin, dosen, mahasiswa           |
| remember_token   | varchar(100)  | Token untuk remember me (opsional)       |
| created_at       | timestamp     | Waktu pembuatan                          |
| updated_at       | timestamp     | Waktu pembaruan                          |

### Tabel: `mahasiswa`
| Field      | Tipe Data     | Keterangan                            |
|------------|---------------|----------------------------------------|
| id         | bigint        | Primary key                            |
| user_id    | bigint        | FK ke `users`                          |
| nim        | varchar(255)  | NIM mahasiswa (unik)                   |
| angkatan   | varchar(255)  | Tahun angkatan                         |
| created_at | timestamp     | Waktu pembuatan                        |
| updated_at | timestamp     | Waktu pembaruan                        |

### Tabel: `dosen`
| Field            | Tipe Data     | Keterangan                            |
|------------------|---------------|----------------------------------------|
| id               | bigint        | Primary key                            |
| user_id          | bigint        | FK ke `users`                          |
| nip              | varchar(255)  | NIP dosen (unik)                       |
| bidang_keahlian  | varchar(255)  | Keahlian bidang studi                  |
| created_at       | timestamp     | Waktu pembuatan                        |
| updated_at       | timestamp     | Waktu pembaruan                        |

### Tabel: `tugas_akhirs`
| Field          | Tipe Data     | Keterangan                                         |
|----------------|---------------|----------------------------------------------------|
| id             | bigint        | Primary key                                        |
| mahasiswa_id   | bigint        | FK ke `mahasiswa`                                  |
| judul          | varchar(255)  | Judul tugas akhir                                  |
| deskripsi      | text          | Deskripsi detail tugas akhir                       |
| file_path      | varchar(255)  | Lokasi file dokumen (nullable)                     |
| status         | enum          | pending, approved, rejected (default: pending)     |
| catatan        | text          | Catatan dari dosen (nullable)                      |
| created_at     | timestamp     | Waktu pembuatan                                    |
| updated_at     | timestamp     | Waktu pembaruan                                    |

### Tabel: `bimbingans`
| Field           | Tipe Data     | Keterangan                                     |
|-----------------|---------------|-------------------------------------------------|
| id              | bigint        | Primary key                                     |
| tugas_akhir_id  | bigint        | FK ke `tugas_akhirs`                            |
| mahasiswa_id    | bigint        | FK ke `mahasiswa` (redundan untuk tracking)     |
| dosen_id        | bigint        | FK ke `dosen` (redundan untuk tracking)         |
| catatan         | text          | Catatan bimbingan dari dosen                    |
| tanggal         | date          | Tanggal sesi bimbingan                          |
| created_at      | timestamp     | Waktu pembuatan                                 |
| updated_at      | timestamp     | Waktu pembaruan                                 |

### Tabel: `dosen_tugas_akhir` *(Pivot)*
| Field           | Tipe Data     | Keterangan                                  |
|-----------------|---------------|----------------------------------------------|
| dosen_id        | bigint        | FK ke `dosen`                                |
| tugas_akhir_id  | bigint        | FK ke `tugas_akhirs`                         |
| created_at      | timestamp     | Timestamp                                    |
| updated_at      | timestamp     | Timestamp                                    |

### Tabel: `password_reset_tokens`
| Field      | Tipe Data     | Keterangan                             |
|------------|---------------|-----------------------------------------|
| email      | varchar(255)  | Email pengguna                          |
| token      | varchar(255)  | Token reset password                    |
| created_at | timestamp     | Waktu pembuatan token                   |

---

## 🔗 Relasi Antar Tabel

### One-to-One
- `users` → `mahasiswa`  
- `users` → `dosen`

### One-to-Many
- `mahasiswa` → `tugas_akhirs`
- `tugas_akhirs` → `bimbingans`
- `dosen` → `bimbingans`

### Many-to-Many
- `dosen` ⇄ `tugas_akhirs` (pivot: `dosen_tugas_akhir`)
