<?php
// public/store.php
require_once __DIR__ . '/src/config.php';
require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/src/repositories/MahasiswaRepository.php';

$config = require __DIR__ . '/../src/config.php';
$db = Database::getInstance($config)->getConnection();
$repo = new MahasiswaRepository($db);

// Basic validation
$errors = [];
$nama = trim($_POST['nama'] ?? '');
$nim = trim($_POST['nim'] ?? '');
$prodi = $_POST['prodi'] ?? '';
$angkatan = $_POST['angkatan'] ?? '';
$status = $_POST['status'] ?? 'aktif';

// required
if($nama === '') $errors[] = "Nama wajib diisi.";
if($nim === '') $errors[] = "NIM wajib diisi.";
if(!in_array($prodi, ['TI','SI','MI'])) $errors[] = "Prodi tidak valid.";
if(!is_numeric($angkatan) || (int)$angkatan < 2000) $errors[] = "Angkatan tidak valid.";

// file handling
$foto_path_db = null;
if(isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['foto'];
    if($file['error'] !== UPLOAD_ERR_OK) $errors[] = "Upload error.";
    if($file['size'] > $config['max_file_size']) $errors[] = "File terlalu besar.";
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if(!in_array($mime, $config['allowed_types'])) $errors[] = "Tipe file tidak diperbolehkan.";
    // move file
    if(empty($errors)) {
        if(!is_dir($config['upload_dir'])) mkdir($config['upload_dir'], 0755, true);
        $ext = $mime === 'image/png' ? '.png' : '.jpg';
        $filename = time() . '_' . preg_replace('/[^A-Za-z0-9_\-]/','',basename($file['name'], $ext)) . $ext;
        $target = $config['upload_dir'] . $filename;
        if(move_uploaded_file($file['tmp_name'], $target)) {
            // store url-relative path for use in HTML
            $foto_path_db = $config['upload_url'] . $filename;
        } else {
            $errors[] = "Gagal menyimpan file.";
        }
    }
}

if(!empty($errors)) {
    echo "<h3>Terjadi error:</h3><ul>";
    foreach($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>";
    echo "</ul><p><a href='create.php'>Kembali</a></p>";
    exit;
}

// insert
$mahasiswa = new Mahasiswa([
    'nama' => $nama,
    'nim' => $nim,
    'prodi' => $prodi,
    'angkatan' => (int)$angkatan,
    'foto_path' => $foto_path_db,
    'status' => $status
]);

try {
    $id = $repo->create($mahasiswa);
    header("Location: list.php");
    exit;
} catch(Exception $ex) {
    echo "Error: " . htmlspecialchars($ex->getMessage());
}
