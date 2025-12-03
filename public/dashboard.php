<?php
require_once __DIR__ . '/src/Auth.php';
Auth::check();
?>

<h2>Selamat datang, <?= htmlspecialchars($_SESSION['username']) ?></h2>

<a href="list.php">Kelola Mahasiswa</a><br>
<a href="logout.php">Logout</a>
