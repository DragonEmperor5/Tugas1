<?php
require_once __DIR__ . '/src/config.php';
require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/src/repositories/MahasiswaRepository.php';

$config = require __DIR__ . '/src/config.php';
$db = Database::getInstance($config)->getConnection();
$repo = new MahasiswaRepository($db);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$student = $repo->find($id);
if(!$student){
    echo "Data tidak ditemukan. <a href='list.php'>Kembali</a>";
    exit;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Detail Mahasiswa</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <nav>Sistem Informasi Mahasiswa</nav>
  
  <div class="container">
    <h2>Detail Mahasiswa</h2>
    
    <div class="detail-box">
      <div class="detail-item">
        <span class="detail-label">Nama:</span>
        <span class="detail-value"><?= htmlspecialchars($student->nama) ?></span>
      </div>
      
      <div class="detail-item">
        <span class="detail-label">NIM:</span>
        <span class="detail-value"><?= htmlspecialchars($student->nim) ?></span>
      </div>
      
      <div class="detail-item">
        <span class="detail-label">Prodi:</span>
        <span class="detail-value"><?= htmlspecialchars($student->prodi) ?></span>
      </div>
      
      <div class="detail-item">
        <span class="detail-label">Angkatan:</span>
        <span class="detail-value"><?= htmlspecialchars($student->angkatan) ?></span>
      </div>
      
      <div class="detail-item">
        <span class="detail-label">Status:</span>
        <span class="detail-value"><?= htmlspecialchars($student->status) ?></span>
      </div>
      
      <?php if($student->foto_path): ?>
      <div class="detail-photo">
        <div class="detail-label">Foto:</div>
        <img src="<?= htmlspecialchars($student->foto_path) ?>" alt="Foto Mahasiswa">
      </div>
      <?php endif; ?>
    </div>
    
    <div class="back-link">
      <a href="list.php" class="btn btn-edit">← Kembali ke Daftar</a>
      <a href="edit.php?id=<?= $student->id ?>" class="btn btn-add">Edit Data</a>
    </div>
  </div>
</body>
</html>