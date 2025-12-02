<?php
require_once __DIR__ . '/src/config.php';
require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/src/repositories/MahasiswaRepository.php';

$config = require __DIR__ . '/src/config.php';
$db = Database::getInstance($config)->getConnection();
$repo = new MahasiswaRepository($db);
$students = $repo->all();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Daftar Mahasiswa</title></head>
<body>
<h1>Daftar Mahasiswa</h1>
<p><a href="create.php">Tambah Mahasiswa</a></p>
<table border="1" cellpadding="6" cellspacing="0">
<thead>
<tr>
  <th>ID</th><th>Nama</th><th>NIM</th><th>Prodi</th><th>Angkatan</th><th>Status</th><th>Foto</th><th>Aksi</th>
</tr>
</thead>
<tbody>
<?php foreach($students as $s): ?>
<tr>
  <td><?=htmlspecialchars($s['id'])?></td>
  <td><?=htmlspecialchars($s['nama'])?></td>
  <td><?=htmlspecialchars($s['nim'])?></td>
  <td><?=htmlspecialchars($s['prodi'])?></td>
  <td><?=htmlspecialchars($s['angkatan'])?></td>
  <td><?=htmlspecialchars($s['status'])?></td>
  <td>
    <?php if($s['foto_path']): ?>
      <img src="<?=htmlspecialchars($s['foto_path'])?>" alt="foto" style="height:50px;">
    <?php endif; ?>
  </td>
  <td>
    <a href="show.php?id=<?= $s['id'] ?>">Show</a> |
    <a href="edit.php?id=<?= $s['id'] ?>">Edit</a> |
    <a href="delete.php?id=<?= $s['id'] ?>" onclick="return confirm('Hapus data ini?')">Delete</a>
  </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</body>
</html>
