<?php
// Aktifkan laporan error untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Memulai session
session_start();

// Masukkan file koneksi ke database
include 'database.php'; // Pastikan koneksi berhasil

// Variabel untuk pesan error
$error_message = '';

// Memproses form jika ada request POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Query untuk mengambil data pengguna berdasarkan username
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username); // "s" berarti string
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika ada data user dengan username tersebut
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password yang dimasukkan dengan password plain text yang disimpan di database
        if ($password === $user['password']) {
            // Mulai session dan simpan informasi login
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Menyimpan role untuk akses selanjutnya

            // Redirect ke Beranda.html setelah login berhasil
            header("Location: Beranda.php");
            exit();  // Menghentikan eksekusi lebih lanjut
        } else {
            // Jika password salah
            $error_message = 'Password salah!';
        }
    } else {
        // Jika username tidak ditemukan
        $error_message = 'Username tidak ditemukan!';
    }

    // Tutup prepared statement dan koneksi database
    $stmt->close();
}

// Tutup koneksi database
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="Login.css">
    <script>
        // Fungsi untuk menampilkan atau menyembunyikan password
        function togglePassword() {
            var passwordField = document.getElementById('password');
            var showPasswordCheckbox = document.getElementById('show-password');
            
            // Jika checkbox dicentang, ganti type input menjadi text (menampilkan password)
            if (showPasswordCheckbox.checked) {
                passwordField.type = 'text';
            } else {
                passwordField.type = 'password';
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <img src="doodle.jpg" alt="Doodle" class="doodle-image">
        </div>
        <div class="right-section">
            <!-- Form hanya meminta username dan password -->
            <form class="login-form" method="POST" action="Login.php">
                <h2>Log in</h2>

                <!-- Username -->
                <label for="username">Nama Lengkap</label>
                <input type="text" id="username" name="username" required>

                <!-- Password -->
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <!-- Checkbox untuk menunjukkan atau menyembunyikan password -->
                <label>
                    <input type="checkbox" id="show-password" onclick="togglePassword()"> Show Password
                </label>

                <!-- Forgot Password Link -->
                <a href="#" class="forgot-password">Forgot Password?</a>

                <!-- Login Button -->
                <button type="submit" class="login-button">Login</button>

                <!-- Link to create account -->
                <a href="BuatAkun.html" class="create-account">Buat akun</a>
                <br>

                <!-- Display Error Message -->
                <?php if (!empty($error_message)): ?>
                    <p style="color: white;"><?php echo $error_message; ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>
