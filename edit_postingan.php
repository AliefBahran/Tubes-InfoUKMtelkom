<?php
include 'connect.php';

// Ambil data berdasarkan ID
$id = $_GET['id'];
$query = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$query->execute([$id]);
$post = $query->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image_path = $post['image_path']; // Path default (jika gambar tidak diperbarui)

    // Ambil tanggal dan waktu dari input terpisah
    $date = $_POST['date']; // Tanggal
    $time = $_POST['time']; // Waktu
    $created_at = $date . ' ' . $time; // Gabungkan menjadi format datetime
    // Proses file gambar baru (jika ada)
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $fileName = basename($_FILES['image']['name']);
        $targetFilePath = $uploadDir . $fileName;

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['image']['type'], $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $image_path = $targetFilePath; // Update path jika upload berhasil
            }
        }
    }

    // Update database
    $sql = "UPDATE posts SET username = ?, title = ?, content = ?, image_path = ?, created_at = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $title, $content, $image_path, $created_at, $id]);

    header("Location: beranda.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <a href="Beranda.php" class="text-sm text-muted">Back</a>
    <div class="container mt-5">
        <h1>Update Post</h1>

        <!-- Form Update Post -->
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($post['username']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($post['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" id="content" name="content" rows="5" required><?= htmlspecialchars($post['content']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Upload New Image</label>
                <input type="file" class="form-control" id="image" name="image">
                <small>Current Image: <a href="<?= htmlspecialchars($post['image_path']); ?>" target="_blank">View</a></small>
            </div>
            <!-- Tanggal dan Waktu -->
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <!-- Menampilkan tanggal yang sudah ada -->
                <input type="date" class="form-control" id="date" name="date" value="<?= substr($post['created_at'], 0, 10); ?>" required>
            </div>
            <div class="mb-3">
                <label for="time" class="form-label">Time</label>
                <!-- Menampilkan waktu yang sudah ada -->
                <input type="time" class="form-control" id="time" name="time" value="<?= substr($post['created_at'], 11, 5); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Post</button>
        </form>
    </div>
</body>

</html>