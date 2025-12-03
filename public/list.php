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
<head>
  <meta charset="utf-8">
  <title>Daftar Mahasiswa</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <nav>Sistem Informasi Mahasiswa</nav>
  
  <div class="container">
    <h2>Daftar Mahasiswa</h2>
    <p><a href="create.php" class="btn btn-add">+ Tambah Mahasiswa</a></p>
    
    <table class="table">
      <thead>
        <tr>
          <th>ID</th><th>Nama</th><th>NIM</th><th>Prodi</th>
          <th>Angkatan</th><th>Status</th><th>Foto</th><th>Aksi</th>
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
            <a href="show.php?id=<?= $s['id'] ?>" class="btn btn-add">Show</a>
            <a href="edit.php?id=<?= $s['id'] ?>" class="btn btn-edit">Edit</a>
            <a href="delete.php?id=<?= $s['id'] ?>" class="btn btn-delete" 
               onclick="return confirm('Hapus data ini?')">Delete</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>