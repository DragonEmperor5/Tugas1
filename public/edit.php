<?php
require_once __DIR__ . '/src/config.php';
require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/src/repositories/MahasiswaRepository.php';

$config = require __DIR__ . '/../src/config.php';
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
<html><head><meta charset="utf-8"><title>Edit Mahasiswa</title></head>
<body>
<h1>Edit Mahasiswa</h1>
<form action="update.php" method="post" enctype="multipart/form-data">
  <input type="hidden" name="id" value="<?=htmlspecialchars($student->id)?>">
  <label>Nama: <input type="text" name="nama" required maxlength="100" value="<?=htmlspecialchars($student->nama)?>"></label><br>
  <label>NIM: <input type="text" name="nim" required maxlength="20" value="<?=htmlspecialchars($student->nim)?>"></label><br>
  <label>Prodi:
    <select name="prodi" required>
      <option value="TI" <?= $student->prodi === 'TI' ? 'selected' : '' ?>>Teknik Informatika</option>
      <option value="SI" <?= $student->prodi === 'SI' ? 'selected' : '' ?>>Sistem Informasi</option>
      <option value="MI" <?= $student->prodi === 'MI' ? 'selected' : '' ?>>Manajemen Informatika</option>
    </select>
  </label><br>
  <label>Angkatan: <input type="number" name="angkatan" required min="2000" max="2100" value="<?=htmlspecialchars($student->angkatan)?>"></label><br>
  <label>Foto (kosongkan untuk mempertahankan): <input type="file" name="foto"></label><br>
  <?php if($student->foto_path): ?>
    <img src="<?=htmlspecialchars($student->foto_path)?>" style="height:80px;"><br>
  <?php endif; ?>
  <label>Status:
    <select name="status" required>
      <option value="aktif" <?= $student->status === 'aktif' ? 'selected' : '' ?>>Aktif</option>
      <option value="nonaktif" <?= $student->status === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
    </select>
  </label><br>
  <button type="submit">Update</button>
</form>
<p><a href="list.php">Kembali</a></p>
</body></html>
