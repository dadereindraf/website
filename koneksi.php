<?php
// Konfigurasi koneksi ke database
$host = "localhost"; // Nama host database
$user = "root"; // Username database
$password = ""; // Password database
$database = "ta"; // Nama database

// Membuat koneksi ke database
$koneksi = new mysqli($host, $user, $password, $database);

// Memeriksa koneksi
if ($koneksi->connect_error) {
    die("Koneksi ke database gagal: " . $koneksi->connect_error);
}
?>
