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

// optional: remove file from disk
// if($student->foto_path) { $path = __DIR__ . $student->foto_path; if(file_exists($path)) unlink($path); }

$repo->delete($id);
header("Location: list.php");
exit;
