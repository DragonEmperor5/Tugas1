<?php
require_once __DIR__ . '/src/config.php';
require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/src/repositories/MahasiswaRepository.php';

// Initialize
$config = require __DIR__ . '/src/config.php';
$db = Database::getInstance($config)->getConnection();
$repo = new MahasiswaRepository($db);

// Get student data
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$student = $repo->find($id);

if (!$student) {
    die("Data tidak ditemukan. <a href='list.php'>Kembali</a>");
}

// Validation
$errors = [];
$nama = trim($_POST['nama'] ?? '');
$nim = trim($_POST['nim'] ?? '');
$prodi = $_POST['prodi'] ?? '';
$angkatan = $_POST['angkatan'] ?? '';
$status = $_POST['status'] ?? 'aktif';

// Validate input
if (empty($nama)) {
    $errors[] = "Nama wajib diisi.";
}

if (empty($nim)) {
    $errors[] = "NIM wajib diisi.";
}

if (!in_array($prodi, ['TI', 'SI', 'MI'])) {
    $errors[] = "Prodi tidak valid.";
}

if (!is_numeric($angkatan) || (int)$angkatan < 2000) {
    $errors[] = "Angkatan tidak valid.";
}

// File upload handling (optional)
$foto_path_db = $student->foto_path;

if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['foto'];
    
    // Check upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Upload error.";
    }
    
    // Check file size
    if ($file['size'] > $config['max_file_size']) {
        $errors[] = "File terlalu besar (max 2MB).";
    }
    
    // Check file type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mime, $config['allowed_types'])) {
        $errors[] = "Tipe file tidak diperbolehkan. Hanya JPG/PNG.";
    }
    
    // If no errors, process upload
    if (empty($errors)) {
        // Create upload directory if not exists
        if (!is_dir($config['upload_dir'])) {
            mkdir($config['upload_dir'], 0755, true);
        }
        
        // Generate unique filename
        $ext = $mime === 'image/png' ? '.png' : '.jpg';
        $filename = time() . '_' . preg_replace('/[^A-Za-z0-9_\-]/', '', basename($file['name'], $ext)) . $ext;
        $target = $config['upload_dir'] . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $target)) {
            $foto_path_db = $config['upload_url'] . $filename;
            
            // Optional: Delete old photo file
            if ($student->foto_path && file_exists(__DIR__ . '/' . $student->foto_path)) {
                unlink(__DIR__ . '/' . $student->foto_path);
            }
        } else {
            $errors[] = "Gagal menyimpan file.";
        }
    }
}

// Display errors if any
if (!empty($errors)) {
    echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Error</title>";
    echo "<link rel='stylesheet' href='style.css'></head><body>";
    echo "<nav>Sistem Informasi Mahasiswa</nav>";
    echo "<div class='container'>";
    echo "<h2>Terjadi Error</h2>";
    echo "<div style='background:#f8d7da;color:#721c24;padding:15px;border-radius:6px;border-left:4px solid #dc3545;margin-bottom:20px;'>";
    echo "<ul style='margin:0;padding-left:20px;'>";
    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo "</ul></div>";
    echo "<a href='edit.php?id={$id}' class='btn btn-edit'>← Kembali</a>";
    echo "</div></body></html>";
    exit;
}

// Update student data
$student->nama = $nama;
$student->nim = $nim;
$student->prodi = $prodi;
$student->angkatan = (int)$angkatan;
$student->foto_path = $foto_path_db;
$student->status = $status;

try {
    $repo->update($student);
    header("Location: list.php");
    exit;
} catch (Exception $ex) {
    echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Error</title>";
    echo "<link rel='stylesheet' href='style.css'></head><body>";
    echo "<nav>Sistem Informasi Mahasiswa</nav>";
    echo "<div class='container'>";
    echo "<h2>Error Database</h2>";
    echo "<div style='background:#f8d7da;color:#721c24;padding:15px;border-radius:6px;border-left:4px solid #dc3545;margin-bottom:20px;'>";
    echo "<p>" . htmlspecialchars($ex->getMessage()) . "</p>";
    echo "</div>";
    echo "<a href='edit.php?id={$id}' class='btn btn-edit'>← Kembali</a>";
    echo "</div></body></html>";
}