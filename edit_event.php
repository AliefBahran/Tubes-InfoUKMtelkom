<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connect.php';

// Ambil data berdasarkan ID
$id = $_GET['id'];
$query = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$query->execute([$id]);
$event = $query->fetch(PDO::FETCH_ASSOC);

// Periksa jika event tidak ditemukan
if (!$event) {
    die("Event not found!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_title = $_POST['event_title']; // Ganti 'title' dengan 'event_title'
    $description = $_POST['description'];
    $image_path = $event['image_path']; // Path default (jika gambar tidak diperbarui)

    // Ambil tanggal dari input
    $date = $_POST['date']; // Tanggal

    // Proses file gambar baru (jika ada)
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $fileName = basename($_FILES['image']['name']);
        $targetFilePath = $uploadDir . $fileName;

        // Memeriksa tipe file yang diizinkan
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['image']['type'], $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $image_path = $targetFilePath; // Update path jika upload berhasil
            } else {
                echo "Error uploading image.";
            }
        } else {
            echo "Invalid file type.";
        }
    }

    // Update database dengan 'event_title' dan nama kolom lainnya yang sesuai
    $sql = "UPDATE events SET event_title = ?, description = ?, image_path = ?, date = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);

    // Eksekusi query dengan parameter yang sesuai
    if ($stmt->execute([$event_title, $description, $image_path, $date, $id])) {
        // Jika berhasil, redirect ke halaman events_list.php
        header("Location: Beranda.php");
        exit;
    } else {
        echo "Error updating event.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <a href="Beranda.php" class="text-sm text-muted">Back</a>
    <div class="container mt-5">
        <h1>Update Event</h1>

        <!-- Form Update Event -->
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="event_title" class="form-label">Event Title</label>
                <input type="text" class="form-control" id="event_title" name="event_title" value="<?= htmlspecialchars($event['event_title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Event Description</label>
                <textarea class="form-control" id="description" name="description" rows="5" required><?= htmlspecialchars($event['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Upload New Image</label>
                <input type="file" class="form-control" id="image" name="image">
                <small>Current Image: <a href="<?= htmlspecialchars($event['image_path']); ?>" target="_blank">View</a></small>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date (e.g., 24 November 2024)</label>
                <input type="text" class="form-control" id="date" name="date" placeholder="24 November 2024" required value="<?= htmlspecialchars($event['date']); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update Event</button>
        </form>
    </div>
</body>

</html>