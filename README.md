# Sistem Informasi Mahasiswa (PHP Native + PDO)

Project ini merupakan aplikasi sederhana untuk mengelola data mahasiswa, termasuk:
- CRUD (Create, Read, Update, Delete)
- Upload foto mahasiswa
- Autentikasi pengguna (Register + Login + Logout)
- Middleware proteksi halaman (hanya user login yang dapat mengakses list.php, create.php, dll)

Project ini cocok sebagai latihan untuk memahami:
- PHP Native
- PDO
- Session Login
- Struktur folder rapi
- Upload file aman
- Repository Pattern

---

## 📁 Struktur Folder


crud-backend-php/
│
├── public/
│ ├── index.php
│ ├── login.php
│ ├── register.php
│ ├── logout.php
│ ├── list.php
│ ├── create.php
│ ├── edit.php
│ ├── delete.php
│ ├── show.php
│ ├── store.php
│ ├── update.php
│ ├── style.css
│ └── uploads/ ← folder upload gambar
│
├── src/
│ ├── config.php
│ ├── Database.php
│ ├── models/
│ │ └── Mahasiswa.php
│ └── repositories/
│ └── MahasiswaRepository.php
│
├── README.md
├── schema.sql
└── .gitignore


---

## ⚙️ Instalasi

1. **Clone repository**
git clone https://github.com/DragonEmperor5/Tugas1.git

2. **Masuk ke folder project**
cd project

3. **Import database**  
Buat database baru, misalnya `kampus_db`, lalu import file SQL berikut:

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    nim VARCHAR(20) UNIQUE,
    prodi VARCHAR(5),
    angkatan INT,
    foto_path VARCHAR(255),
    status VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
4. **Sesuaikan konfigurasi database di src/config.php** 

'db' => [
    'host' => '127.0.0.1',
    'dbname' => 'kampus_db',
    'user' => 'root',
    'pass' => '',
]

3. **Cara Menjalankan**  

Jika pakai PHP built-in server:

php -S localhost:8000 -t public

Fitur Autentikasi
Register

User membuat akun di register.php

Password di-hash menggunakan password_hash()

Login

Verifikasi username + password

Jika berhasil → simpan session

Middleware

Untuk membatasi halaman:

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

Ditambahkan di file:

list.php

create.php

edit.php

update.php

delete.php

👤 Menampilkan Nama User Login

Pada navbar:

echo "Halo, " . htmlspecialchars($_SESSION['username']);

 Upload Foto Mahasiswa

Folder upload: public/uploads/

Validasi file:

Maksimal 2MB

Tipe: .jpg / .png

File disimpan dengan nama unik:

timestamp_uniqid.jpg