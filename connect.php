<?php
// Mengaktifkan error reporting untuk menampilkan semua error yang terjadi
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Konfigurasi koneksi database
$host = 'localhost'; // Host database
$dbname = 'infoUKMtelkom'; // Nama database
$username = 'root'; // Username database
$password = ''; // Password database

try {
    // Mencoba untuk melakukan koneksi ke database menggunakan PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set atribut untuk menangani error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Jika koneksi berhasil, tampilkan pesan sukses
    
} catch (PDOException $e) {
    // Jika terjadi kesalahan, tampilkan pesan error
    die("Koneksi gagal: " . $e->getMessage());
}
?>
