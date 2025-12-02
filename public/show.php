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
<!doctype html><html><head><meta charset="utf-8"><title>Detail Mahasiswa</title></head><body>
<h1>Detail Mahasiswa</h1>
<p>Nama: <?=htmlspecialchars($student->nama)?></p>
<p>NIM: <?=htmlspecialchars($student->nim)?></p>
<p>Prodi: <?=htmlspecialchars($student->prodi)?></p>
<p>Angkatan: <?=htmlspecialchars($student->angkatan)?></p>
<p>Status: <?=htmlspecialchars($student->status)?></p>
<?php if($student->foto_path): ?>
  <p>Foto:<br><img src="<?=htmlspecialchars($student->foto_path)?>" style="max-height:200px;"></p>
<?php endif; ?>
<p><a href="list.php">Kembali</a></p>
</body></html>
