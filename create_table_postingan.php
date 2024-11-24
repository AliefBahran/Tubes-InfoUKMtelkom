<?php
// Mengaktifkan error reporting untuk menampilkan semua error yang terjadi
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Koneksi ke database
$host = 'localhost';      // Host database
$username = 'root';       // Username database
$password = '';           // Password database
$dbname = 'infoUKMtelkom'; // Nama database

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// SQL query untuk membuat tabel
$sql = "CREATE TABLE IF NOT EXISTS posts (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Eksekusi query untuk membuat tabel
if ($conn->query($sql) === TRUE) {
    echo "Tabel 'posts' berhasil dibuat!";
} else {
    echo "Error saat membuat tabel: " . $conn->error;
}

// Tutup koneksi
$conn->close();
?>
