<?php
return [
    'db' => [
        'host' => '127.0.0.1',
        'dbname' => 'kampus_db',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],

    
    'upload_dir' => realpath(__DIR__ . '/../uploads/') . '/',

    
    'upload_url' => '/uploads/',

    
    'max_file_size' => 2 * 1024 * 1024,
    'allowed_types' => ['image/jpeg', 'image/png']
];
