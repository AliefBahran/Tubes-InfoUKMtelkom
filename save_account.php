<?php
// Aktifkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Periksa apakah metode yang digunakan adalah POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Method Not Allowed');
}

// Ambil data dari form menggunakan $_POST
$username = $_POST['username'] ?? '';
$nim = $_POST['nim'] ?? '';
$fakultas = $_POST['fakultas'] ?? '';
$jurusan = $_POST['jurusan'] ?? '';
$email = $_POST['email'] ?? '';
$nohp = $_POST['nohp'] ?? '';
$password = $_POST['password'] ?? '';

// Validasi input
if (empty($username) || empty($nim) || empty($fakultas) || empty($jurusan) || empty($email) || empty($nohp) || empty($password)) {
    die('Semua data harus diisi');
}

// Koneksi ke database (gunakan informasi koneksi yang sesuai)
$servername = "localhost";
$username_db = "root";  // Ganti dengan username database Anda
$password_db = "";      // Ganti dengan password database Anda
$dbname = "InfoUKMtelkom";     // Ganti dengan nama database Anda

// Membuat koneksi ke MySQL
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die('Gagal terhubung ke database: ' . $conn->connect_error);
}

// Escape input untuk keamanan
$username = $conn->real_escape_string($username);
$nim = $conn->real_escape_string($nim);
$fakultas = $conn->real_escape_string($fakultas);
$jurusan = $conn->real_escape_string($jurusan);
$email = $conn->real_escape_string($email);
$nohp = $conn->real_escape_string($nohp);

// *Tidak menggunakan password_hash()*: Menyimpan password langsung tanpa enkripsi
$plainPassword = $password;  // Password asli tanpa di-hash

// Query untuk menyimpan data pengguna
$sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$plainPassword', 'Pengunjung')";
if ($conn->query($sql) === TRUE) {
    $userId = $conn->insert_id;
    // Simpan data profil pengguna
    $sqlProfile = "INSERT INTO user_profiles (user_id, nim, fakultas, jurusan, email, no_telpon) 
                   VALUES ('$userId', '$nim', '$fakultas', '$jurusan', '$email', '$nohp')";
    if ($conn->query($sqlProfile) === TRUE) {
        // Menampilkan pesan keberhasilan
        echo "Akun berhasil dibuat!<br>";

        // Tombol untuk menuju halaman login
        echo '<a href="Login.php"><button>Ke Halaman Login</button></a>';
    } else {
        echo "Gagal menyimpan profil pengguna: " . $conn->error;
    }
} else {
    echo "Gagal membuat akun: " . $conn->error;
}

// Tutup koneksi
$conn->close();
