<?php
// src/models/Mahasiswa.php
class Mahasiswa {
    public $id;
    public $nama;
    public $nim;
    public $prodi;
    public $angkatan;
    public $foto_path;
    public $status;

    public function __construct(array $data = []) {
        foreach ($data as $k => $v) {
            if(property_exists($this, $k)) $this->$k = $v;
        }
    }
}
