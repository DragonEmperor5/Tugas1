<?php
// src/Database.php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct(array $config) {
        $db = $config['db'];
        $dsn = "mysql:host={$db['host']};dbname={$db['dbname']};charset={$db['charset']}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        $this->pdo = new PDO($dsn, $db['user'], $db['pass'], $options);
    }

    public static function getInstance(array $config) {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}
