<?php
// ----------------------
// 1) Show errors during development (turn off in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2) Start session (only once, before any output)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 3) Database credentials — ubah sesuai environment kamu
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'TestProject';

// 4) Connect using mysqli (procedural style, mudah dipahami)
$koneksi = @mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// 5) Fail fast: tampilkan pesan singkat jika koneksi gagal
if (!$koneksi) {
    // Die supaya script lain tidak jalan tanpa DB — lebih aman untuk project tugas
    die("Database connection error: " . mysqli_connect_error());
}

// 6) Set charset supaya tidak muncul masalah karakter (emoji, bahasa, dll)
mysqli_set_charset($koneksi, "utf8mb4");

// Ready: gunakan $koneksi dan session di file lain
