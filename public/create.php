<?php
// public/create.php
?>
<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Tambah Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
  </head>
<body>
<h1>Tambah Mahasiswa</h1>
<form action="store.php" method="post" enctype="multipart/form-data">
  <label>Nama: <input type="text" name="nama" required maxlength="100"></label><br>
  <label>NIM: <input type="text" name="nim" required maxlength="20"></label><br>
  <label>Prodi:
    <select name="prodi" required>
      <option value="">--Pilih--</option>
      <option value="TI">Teknik Informatika</option>
      <option value="SI">Sistem Informasi</option>
      <option value="MI">Manajemen Informatika</option>
    </select>
  </label><br>
  <label>Angkatan: <input type="number" name="angkatan" required min="2000" max="2100"></label><br>
  <label>Foto (jpg/png, &lt; 2MB): <input type="file" name="foto"></label><br>
  <label>Status:
    <select name="status" required>
      <option value="aktif">Aktif</option>
      <option value="nonaktif">Nonaktif</option>
    </select>
  </label><br>
  <button type="submit">Simpan</button>
</form>
<p><a href="list.php">Kembali</a></p>
</body></html>
