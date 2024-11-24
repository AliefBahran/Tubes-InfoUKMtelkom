<?php
$host = "localhost";
$user = "root"; // Ganti dengan username MySQL Anda
$password = ""; // Ganti dengan password MySQL Anda
$dbname = "infoUKMtelkom"; // Nama database Anda

// Buat koneksi
$conn = new mysqli($host, $user, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>