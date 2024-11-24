<?php
include 'connect.php';
session_start(); // Memulai sesi untuk mengambil role dari pengguna yang login

// Pastikan pengguna sudah login dan memiliki session role
if (isset($_SESSION['role'])) {
    // Periksa role pengguna
    $is_pengunjung = ($_SESSION['role'] == 'Pengunjung') ? true : false;
    $is_pengurus_ukm = ($_SESSION['role'] == 'Pengurus UKM') ? true : false;
    $is_anggota_ukm = ($_SESSION['role'] == 'Anggota UKM') ? true : false;
} else {
    // Jika tidak ada sesi, artinya pengguna belum login
    $is_pengunjung = false;
    $is_pengurus_ukm = false;
    $is_anggota_ukm = false;
}

// Ambil data dari database untuk posts
$query = $pdo->prepare("SELECT *, DATE_FORMAT(created_at, '%Y-%m-%d') AS date, DATE_FORMAT(created_at, '%H:%i') AS time FROM posts");
$query->execute();
$posts = $query->fetchAll(PDO::FETCH_ASSOC);

// Ambil data dari tabel event
$queryEvents = $pdo->prepare("SELECT * FROM events ORDER BY date DESC");
$queryEvents->execute(); // Eksekusi query untuk mendapatkan data event
$events = $queryEvents->fetchAll(PDO::FETCH_ASSOC); // Ambil data event dalam bentuk array

// Jika tidak ada data event, set $events menjadi array kosong
if ($events === false) {
    $events = [];
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda</title>
    <!-- Link ke Font Awesome untuk ikon profesional -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="zz.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<style>
    .modal-dialog {
        position: fixed;
        /* Modal akan berada di posisi tetap */
        bottom: 100px;
        /* Sesuaikan agar modal naik sedikit dari bawah */
        left: 60px;
        /* Sesuaikan dengan jarak tombol */
        max-width: 550px;
        /* Batas lebar modal */
        width: 550px;
        /* Lebar modal */
        height: auto;
        /* Agar tinggi menyesuaikan konten */
        margin: 0;
        /* Menghapus margin default */
        z-index: 1050;
        /* Di atas konten lainnya */
    }

    #profileButton {
        margin-top: auto;
        position: fixed;
        /* Agar tombol tetap berada di sudut bawah kiri */
        bottom: 20px;
        /* Jarak dari bawah */
        left: 20px;
        /* Jarak dari kiri */
        z-index: 1050;
        /* Pastikan tombol berada di atas konten lainnya */
        border: none;
        background: none;
    }

    .btn-logout {
        background-color: white;
        color: #A14043;
        border: #555;
        border-radius: 25px;
        font-weight: bold;
    }

    .modal-content {
        background-color: #D9D9D9;
        border-radius: 25px;
        height: 100%;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        /* Bayangan */
    }

    .modal-header {
        color: black;
        justify-content: center;
        align-items: center;
        display: flex;
        position: relative;
        padding: 20px;
    }

    .btn-close {
        position: absolute;
        /* Menempatkan tombol close di sudut kanan atas */
        right: 10px;
        /* Menempatkan tombol dekat dengan sisi kanan */
        top: 50%;
        /* Menempatkan tombol pada tengah vertikal */
        transform: translateY(-50%);
        /* Menyeimbangkan posisi tombol close secara vertikal */
    }

    .profile-image {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        /* Membuat gambar profil bulat */
        object-fit: cover;
        /* Memastikan gambar tetap proporsional */
    }

    .datadiri {
        background-color: #A14043;
        color: white;
        /* Teks putih */
        padding: 15px;
        border-radius: 25px;
        margin-bottom: 20px;
    }

    .mb-3.deskripsi {
        display: flex;
        /* Menggunakan Flexbox untuk kontrol layout */
        flex-direction: column;
        /* Menyusun elemen secara vertikal */
        justify-content: flex-start;
        /* Menyusun konten ke atas */
        align-items: flex-start;
        /* Menyusun konten ke kiri */
        width: 450px;
        /* Lebar kontainer (misalnya, sesuaikan dengan kebutuhan) */
        height: 150px;
        /* Tinggi kontainer (misalnya, sesuaikan dengan kebutuhan) */
        padding: 10px;
        /* Menambahkan jarak di dalam kontainer */
        background-color: #f8f8f8;
        /* Warna latar belakang (sesuaikan jika diperlukan) */
        border-radius: 15px;
        /* Menambahkan sudut melengkung (optional) */
    }

    /* Styling untuk elemen pembungkus informasi */
    .info-container {
        background-color: #A14043;
        color: white;
        /* Teks putih */
        padding: 15px;
        border-radius: 15px;
        margin-bottom: 20px;
    }

    .info-container p {
        margin: 10px 0;
    }

    .info-container .badge {
        font-size: 14px;
    }

    .modal-title {
        font-weight: bold;
    }

    .modal-body {
        font-size: 14px;
        padding: 30px;
        /* Memberikan padding agar konten tidak terlalu rapat dengan tepi modal */
        overflow-y: auto;
        /* Menambahkan scroll jika konten lebih tinggi dari modal */
    }

    .modal-footer {
        border-top: none;
        display: flex;
        justify-content: center;
        /* Menempatkan tombol di tengah */
        align-items: center;
        /* Menyelaraskan secara vertikal */
        padding-top: 20px;
    }

    .badge {
        background-color: #A84343;
    }

    img {
        width: 80px;
        height: 80px;
        object-fit: cover;
    }

    .see-more {
        color: red !important;
    }

    .daftar-ukm {
        color: red !important;
        text-decoration: none;
    }

    .card {
        border: none;
        border-radius: 10px;
        background-color: #fff;
    }

    .card-title {
        font-size: 1.2rem;
        font-weight: bold;
    }

    .card-text {
        font-size: 0.9rem;
        color: #555;
    }

    .bi-heart {
        color: #d63031;
        cursor: pointer;
    }

    .card-body {
        text-align: left;
        /* Mengubah teks menjadi rata kiri */
    }

    .image-container {
        width: 100%;
        /* Wadah memiliki ukuran tetap */
        height: 200px;
        /* Tetapkan tinggi tetap */
        overflow: hidden;
        /* Potong gambar di luar wadah */
        border: 1px solid #ccc;
        /* Opsional: untuk melihat batas wadah */
    }

    .image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Sesuaikan gambar sesuai wadah tanpa mengubah aspek rasio */
    }

    .row {
        margin-right: 0;
        margin-left: 0;
    }
</style>

<body>
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column align-items-center">
        <a href="#"><i class="icon fas fa-home"></i></a>
        <a href="#"><i class="icon fas fa-bell"></i></a>
        <a href="Pencarian.html"><i class="icon fas fa-search"></i></a>
        <button type="button" id="profileButton" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#dashboardModal">
            <i class="fas fa-user-circle fa-2x text-white"></i> <!-- Ikon profil berwarna putih -->
        </button>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="dashboardModal" tabindex="-1" aria-labelledby="dashboardModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dashboardModalLabel">Dashboard Personal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="info-container d-flex align-items-center mb-3">
                        <img src="https://via.placeholder.com/80" class="profile-image me-3" alt="User Photo">
                        <div>
                            <p><strong>Nama:</strong> Muhammad Raul</p>
                            <p class="fw-bold mb-0">NIM: 607012330144</p>
                            <p class="fw-bold mb-0">Member of:</p>
                            <p><span class="badge bg-secondary">ukmbolatelkomuniversity</span></p>
                        </div>
                    </div>


                    <div class="mb-3 datadiri">
                        <p class="fw-bold mb-0">Joined since: December 17, 2024</p>
                    </div>

                    <div class="mb-3 datadiri">
                        <p class="fw-bold mb-0">Role: Member</p>
                    </div>

                    <div class="mb-3 datadiri">
                        <p class="fw-bold mb-0">Change user role:</p>
                        <select class="form-select">
                            <option selected>Member</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div class="mb-3 deskripsi">
                        <p><span class="fw-bold teks">Ada</span><br>1 Apr 2024</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-logout">Log out</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Content Wrapper -->
    <div class="main-wrapper container">
        <!-- New Post Section -->
        <div class="new-post mt-5">
            <?php if ($is_pengunjung): ?>
                <h4><a href="DaftarUkm.html">“Register for student activity clubs ?”</a></h4>
            <?php endif; ?>
            <div class="container">
                <div class="row mb-4">
                    <div class="row mb-4">
                        <div class="col-12 d-flex justify-content-between align-items-center">
                            <h2 class="text-xl font-bold">New Post</h2>
                            <a href="berita.php" class="text-sm text-muted">See more</a>
                        </div>
                        <!-- Tombol Add New Post berada di bawah -->
                        <div class="col-12">
                            <?php if ($is_pengurus_ukm): ?>
                                <div class="col-12">
                                    <a href="create_postingan.php" class="btn btn-success w-100">Add New Post</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <?php foreach ($posts as $post): ?>
                        <!-- Post Cards -->
                        <div class="col-12 mb-4">
                            <div class="card shadow-sm">
                                <!-- Bagian Atas: Posted By -->
                                <div class="card-header d-flex align-items-center" style="background-color: #f8f9fa; border-bottom: none;">
                                    <img src="97242732_237533537524197_4474686594728591360_n (1).jpg" alt="User Profile" class="rounded-circle me-3" style="width: 40px; height: 40px;">
                                    <div>
                                        <h6 class="mb-0 fw-bold"><?= htmlspecialchars($post['username']); ?></h6>
                                    </div>
                                </div>
                                <div class="card-img-top-container">
                                    <img src="<?= htmlspecialchars($post['image_path']); ?>" class="card-img-top img-fluid rounded-top" alt="Post Image">
                                </div>
                                <!-- Tanggal dan Jam -->
                                <div class="card-meta d-flex justify-content-between px-3 py-2">
                                    <small class="text-muted date"><?= htmlspecialchars($post['date']); ?></small>
                                    <small class="text-muted time"><?= htmlspecialchars($post['time']); ?></small>
                                </div>
                                <!-- Bagian Tengah: Konten -->
                                <div class="card-body">
                                    <h5 class="card-title mb-3 fw-bold"><?= htmlspecialchars($post['title']); ?></h5>
                                    <p class="card-text text-muted">
                                        <?= nl2br(htmlspecialchars($post['content'])); ?>
                                    </p>
                                </div>
                                <!-- Bagian Bawah: Footer -->
                                <div class="card-footer">
                                    <!-- Teks "736 People Saw" dan ikon bi yang diletakkan di kanan -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-muted small">736 People Saw</div>
                                        <div class="d-flex align-items-center ms-auto">
                                            <i class="bi bi-save2 me-3" style="font-size: 1.2rem; cursor: pointer;"></i>
                                            <i class="bi bi-share" style="font-size: 1.2rem; cursor: pointer;"></i>
                                        </div>
                                    </div>

                                    <!-- Tombol Edit dan Delete, hanya tampil untuk Pengurus UKM -->
                                    <?php if ($is_pengurus_ukm): ?>
                                        <div class="card-footer d-flex justify-content-start mt-2">
                                            <!-- Tombol Edit -->
                                            <a href="edit_postingan.php?id=<?= $post['id']; ?>" class="btn btn-primary btn-sm">Edit</a>

                                            <!-- Tombol Delete dengan jarak tipis di sebelah kanan tombol Edit -->
                                            <a href="delete_postingan.php?id=<?= $post['id']; ?>" class="btn btn-danger btn-sm ms-2">Delete</a>
                                        </div>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                    <!-- Ulangi untuk setiap post -->
                    <div class="col-12 mb-4">
                        <div class="card shadow-sm">
                            <!-- Bagian Atas: Posted By -->
                            <div class="card-header d-flex align-items-center" style="background-color: #f8f9fa; border-bottom: none;">
                                <img src="97242732_237533537524197_4474686594728591360_n (1).jpg" alt="User Profile" class="rounded-circle me-3" style="width: 40px; height: 40px;">
                                <div>
                                    <h6 class="mb-0 fw-bold">ukmbolatelu</h6>
                                </div>
                            </div>
                            <div class="card-img-top-container">
                                <img src="462500569_1526509842076945_1597993543926090876_n.jpg" class="card-img-top img-fluid rounded-top" alt="Post Image">
                            </div>
                            <!-- Tanggal dan Jam -->
                            <div class="card-meta d-flex justify-content-between px-3 py-2">
                                <small class="text-muted date">5 March 2024</small>
                                <small class="text-muted time">17:34 PM</small>
                            </div>
                            <!-- Bagian Tengah: Konten -->
                            <div class="card-body">
                                <h5 class="card-title mb-3 fw-bold">1 - 0 FULL TIME LIFMAJAB 2024</h5>
                                <p class="card-text text-muted">
                                    Futsal Telkom berhasil mengalahkan futsal Widyatama dengan skor tipis 1-0
                                </p>
                            </div>
                            <!-- Bagian Bawah: Footer -->
                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <div class="text-muted small">736 People Saw</div>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-save2 me-3" style="font-size: 1.2rem; cursor: pointer;"></i>
                                    <i class="bi bi-share" style="font-size: 1.2rem; cursor: pointer;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Planner, Events, and UKM Wrapper -->
        <div class="planner-ukm-wrapper container mt-5">
            <div class="planner">
                <h3>Planner</h3>
                <div id="novemberCalendar"></div>
                <div id="eventListPanel">
                    <h2>Activities</h2>
                    <ul id="eventList"></ul>
                </div>
            </div>

            <div class="container mt-3">
                <div class="row">
                    <!--Events Section -->
                    <div class="col-md-12 mb-4">
                        <div class="events-section-container">
                            <div class="header d-flex justify-content-between">
                                <h2 class="text-xl font-bold">Live Events</h2>
                                <a class="text-sm text-gray-500" href="#">See more</a>
                            </div>

                            <div class="container mt-4">
                                <!-- Tombol Tambah Event di Atas -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <?php
                                        // Cek apakah pengguna memiliki role 'Pengurus UKM'
                                        if (isset($_SESSION['role']) && $_SESSION['role'] == 'Pengurus UKM') {
                                            // Jika role adalah 'Pengurus UKM', tampilkan tombol "Tambah Event"
                                            echo '<a href="create_event.php" class="btn btn-primary">Tambah Event</a>';
                                        }
                                        ?>
                                    </div>
                                </div>

                                <!-- Bagian Daftar Event -->
                                <div class="row">
                                    <?php foreach ($events as $event): ?>
                                        <div class="col-12 col-lg-3 mb-4">
                                            <div class="events live-events text-center p-3 border rounded shadow-sm">
                                                <!-- Format tanggal ke format '15 November, 2024' -->
                                                <p class="text-muted mb-2">
                                                    <?php
                                                    $formattedDate = date('d F, Y', strtotime($event['date']));
                                                    echo htmlspecialchars($formattedDate);
                                                    ?>
                                                </p>
                                                <!-- Gambar -->
                                                <img src="<?= htmlspecialchars($event['image_path']); ?>" alt="Event Image" class="img-fluid rounded mb-3">
                                                <!-- Judul event -->
                                                <h5 class="font-bold"><?= htmlspecialchars($event['event_title']); ?></h5>
                                                <!-- Deskripsi -->
                                                <p class="text-sm text-gray-700"><?= htmlspecialchars($event['description']); ?></p>

                                                <!-- Tombol Edit dan Delete -->
                                                <div class="d-flex justify-content-between mt-3">
                                                    <?php
                                                    // Cek apakah pengguna memiliki peran 'Pengurus UKM'
                                                    if (isset($_SESSION['role']) && $_SESSION['role'] == 'Pengurus UKM') {
                                                        // Jika role adalah 'Pengurus UKM', tampilkan tombol Edit dan Delete
                                                        echo '<a href="edit_event.php?id=' . $event['id'] . '" class="btn btn-warning btn-sm">Edit</a>';
                                                        echo '<a href="delete_event.php?id=' . $event['id'] . '" class="btn btn-danger btn-sm">Delete</a>';
                                                    }
                                                    ?>
                                                </div>

                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Popup Kalender Tahun -->
        <div id="fullCalendarModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Kalender 2024</h2>
                <div id="fullCalendar"></div>
            </div>
        </div>

        <script src="Beranda.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    </div>
</body>

</html>