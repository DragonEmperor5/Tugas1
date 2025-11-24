<?php
// src/repositories/MahasiswaRepository.php
require_once __DIR__ . '/../models/Mahasiswa.php';

class MahasiswaRepository {
    private $pdo;
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function all(): array {
        $stmt = $this->pdo->query("SELECT * FROM mahasiswa ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function find(int $id) {
        $stmt = $this->pdo->prepare("SELECT * FROM mahasiswa WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? new Mahasiswa($row) : null;
    }

    public function create(Mahasiswa $m) {
        $sql = "INSERT INTO mahasiswa (nama, nim, prodi, angkatan, foto_path, status)
                VALUES (:nama, :nim, :prodi, :angkatan, :foto_path, :status)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nama' => $m->nama,
            ':nim' => $m->nim,
            ':prodi' => $m->prodi,
            ':angkatan' => $m->angkatan,
            ':foto_path' => $m->foto_path,
            ':status' => $m->status
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update(Mahasiswa $m) {
        $sql = "UPDATE mahasiswa SET nama=:nama, nim=:nim, prodi=:prodi, angkatan=:angkatan, foto_path=:foto_path, status=:status WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nama' => $m->nama,
            ':nim' => $m->nim,
            ':prodi' => $m->prodi,
            ':angkatan' => $m->angkatan,
            ':foto_path' => $m->foto_path,
            ':status' => $m->status,
            ':id' => $m->id
        ]);
    }

    public function delete(int $id) {
        $stmt = $this->pdo->prepare("DELETE FROM mahasiswa WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
