<?php
session_start();

$config = require __DIR__ . '/src/config.php';
require_once __DIR__ . '/src/Database.php';

$db = Database::getInstance($config)->getConnection();
$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm']);

    // Validasi
    if ($username === "" || $password === "" || $confirm === "") {
        $errors[] = "Semua field wajib diisi.";
    }

    if (strlen($password) < 4) {
        $errors[] = "Password minimal 4 karakter.";
    }

    if ($password !== $confirm) {
        $errors[] = "Password dan konfirmasi tidak sama.";
    }

    // Cek username
    $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        $errors[] = "Username sudah digunakan.";
    }

    // Insert user
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $ins = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $ins->execute([$username, $hash]);

        $success = "Registrasi berhasil! Mengalihkan ke halaman login...";
        header("Refresh: 2; URL=login.php");
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem Informasi Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-container">
            <h2 style="text-align: center; color: #333; margin-bottom: 10px;">Register</h2>
            <p style="text-align: center; color: #666; margin-bottom: 30px;">
                Buat akun baru untuk mengakses Sistem Informasi Mahasiswa
            </p>

            <?php if (!empty($errors)): ?>
                <div class="alert-error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert-success">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <form method="post" class="auth-form">
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" 
                           placeholder="Pilih username" required autofocus>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" 
                           placeholder="Minimal 4 karakter" required>
                </div>

                <div class="input-group">
                    <label for="confirm">Konfirmasi Password</label>
                    <input type="password" id="confirm" name="confirm" 
                           placeholder="Ulangi password" required>
                </div>

                <button type="submit">Register</button>
            </form>

            <div class="auth-link">
                Sudah punya akun? 
                <a href="login.php">Login di sini</a>
            </div>
        </div>
    </div>
</body>
</html>