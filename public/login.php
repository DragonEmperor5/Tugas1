<?php
session_start();

$config = require __DIR__ . '/src/config.php';
require_once __DIR__ . '/src/Database.php';

$db = Database::getInstance($config)->getConnection();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header("Location: list.php");
        exit;
    } else {
        $errors[] = "Username atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-container">
            <h2 style="text-align: center; color: #333; margin-bottom: 10px;">Login</h2>
            <p style="text-align: center; color: #666; margin-bottom: 30px;">
                Sistem Informasi Mahasiswa
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

            <form method="post" class="auth-form">
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" 
                           placeholder="Masukkan username" required autofocus>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" 
                           placeholder="Masukkan password" required>
                </div>

                <button type="submit">Login</button>
            </form>

            <div class="auth-link">
                Belum punya akun? 
                <a href="register.php">Daftar di sini</a>
            </div>
        </div>
    </div>
</body>
</html>