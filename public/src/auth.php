<?php
// src/Auth.php
class Auth {
    public static function check() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit;
        }
    }
}
