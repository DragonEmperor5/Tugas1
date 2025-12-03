<?php
require_once __DIR__ . '/src/config.php';
require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/src/repositories/MahasiswaRepository.php';

$config = require __DIR__ . '/src/config.php';
$db = Database::getInstance($config)->getConnection();
$repo = new MahasiswaRepository($db);

// Ambil input
$nama = trim($_POST['nama'] ?? '');
$nim = trim($_POST['nim'] ?? '');
$prodi = $_POST['prodi'] ?? '';
$angkatan = $_POST['angkatan'] ?? '';
$status = $_POST['status'] ?? 'aktif';

$errors = [];

// VALIDASI DASAR
if ($nama === '') $errors[] = "Nama wajib diisi.";
if ($nim === '') $errors[] = "NIM wajib diisi.";
if (!in_array($prodi, ['TI', 'SI', 'MI'])) $errors[] = "Prodi tidak valid.";
if (!is_numeric($angkatan) || $angkatan < 2000) $errors[] = "Angkatan tidak valid.";

// ============== CEK NIM SUDAH ADA ATAU BELUM ==============
$stmt = $db->prepare("SELECT COUNT(*) FROM mahasiswa WHERE nim = ?");
$stmt->execute([$nim]);
if ($stmt->fetchColumn() > 0) {
    $errors[] = "NIM sudah digunakan, tidak boleh duplikat.";
}

// UPLOAD FOTO
$foto_path_db = null;

if (!empty($_FILES['foto']['name'])) {

    $file = $_FILES['foto'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Gagal upload file.";
    }

    if ($file['size'] > $config['max_file_size']) {
        $errors[] = "Ukuran file terlalu besar (maks 2MB).";
    }

    // Cek tipe file
    $mime = mime_content_type($file['tmp_name']);
    if (!in_array($mime, $config['allowed_types'])) {
        $errors[] = "Tipe file tidak diizinkan.";
    }

    // Jika lolos semua, upload
    if (empty($errors)) {
        if (!is_dir($config['upload_dir'])) {
            mkdir($config['upload_dir'], 0755, true);
        }

        $ext = ($mime === 'image/png') ? ".png" : ".jpg";
        $filename = $nim . $ext;


        $target = $config['upload_dir'] . $filename;

        if (move_uploaded_file($file['tmp_name'], $target)) {
            $foto_path_db = $config['upload_url'] . $filename;
        } else {
            $errors[] = "Tidak bisa menyimpan file.";
        }
    }
}

// JIKA ADA ERROR
if (!empty($errors)) {
    echo "<h3>Error:</h3><ul>";
    foreach ($errors as $e) {
        echo "<li>" . htmlspecialchars($e) . "</li>";
    }
    echo "</ul><a href='create.php'>Kembali</a>";
    exit;
}

// INSERT DATA
$mahasiswa = new Mahasiswa([
    'nama' => $nama,
    'nim' => $nim,
    'prodi' => $prodi,
    'angkatan' => (int)$angkatan,
    'foto_path' => $foto_path_db,
    'status' => $status
]);

try {
    $repo->create($mahasiswa);
    header("Location: list.php");
    exit;
} catch (Exception $ex) {
    echo "Error database: " . htmlspecialchars($ex->getMessage());
}
