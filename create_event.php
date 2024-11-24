<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil input tanggal dalam format "YYYY-MM-DD"
    $input_date = $_POST['date'];

    // Pastikan input tanggal valid
    $datetime_object = DateTime::createFromFormat('Y-m-d', $input_date);
    if (!$datetime_object || $datetime_object->format('Y-m-d') !== $input_date) {
        $error = "Invalid date format. Please select a valid date.";
    } else {
        $formatted_date = $datetime_object->format('Y-m-d');
    }

    // Ambil input event title dan description
    $event_title = trim($_POST['event_title']);
    $description = trim($_POST['description']);

    // Validasi input event title dan description
    if (empty($event_title) || empty($description)) {
        $error = "Event title and description are required.";
    }

    // Proses file gambar
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $fileName = basename($_FILES['image']['name']);
        $targetFilePath = $uploadDir . $fileName;

        // Validasi file gambar (contohnya memeriksa ekstensi file)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($_FILES['image']['tmp_name']);

        if (!in_array($fileType, $allowedTypes)) {
            $error = "Only JPG, PNG, and GIF files are allowed.";
        } elseif (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            // Simpan data ke database jika file berhasil diupload
            try {
                $sql = "INSERT INTO events (date, image_path, event_title, description) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$formatted_date, $targetFilePath, $event_title, $description]);

                header("Location: beranda.php");
                exit;
            } catch (PDOException $e) {
                $error = "Database error: " . $e->getMessage();
            }
        } else {
            $error = "Failed to upload image.";
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
    <title>Create Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <a href="Beranda.php" class="text-sm text-muted">Back</a>
    <div class="container mt-5">
        <h1>Add New Event</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="event_title" class="form-label">Event Title</label>
                <input type="text" class="form-control" id="event_title" name="event_title" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Upload Image</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Select Date</label>
                <!-- Input untuk memilih tanggal -->
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Event</button>
        </form>
    </div>
</body>

</html>
