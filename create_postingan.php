<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Ambil tanggal dan waktu dari input terpisah
    $date = $_POST['date']; // Tanggal
    $time = $_POST['time']; // Waktu
    $created_at = $date . ' ' . $time; // Gabungkan menjadi format datetime

    // Proses file gambar
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/'; // Folder untuk menyimpan gambar
        $fileName = basename($_FILES['image']['name']);
        $targetFilePath = $uploadDir . $fileName;

        // Validasi tipe file (opsional)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['image']['type'], $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                // Simpan data ke database
                $sql = "INSERT INTO posts (username, title, content, image_path , created_at) VALUES (?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$username, $title, $content, $targetFilePath, $created_at]);

                header("Location: beranda.php");
                exit;
            } else {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Invalid file type. Only JPG, PNG, and GIF are allowed.";
        }
    } else {
        $error = "Please upload an image.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <a href="Beranda.php" class="text-sm text-muted">Back</a>
    <div class="container mt-5">
        <h1>Add New Post</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Upload Image</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <div class="mb-3">
                <label for="time" class="form-label">Time</label>
                <input type="time" class="form-control" id="time" name="time" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Post</button>
        </form>

    </div>
</body>

</html>