<?php
include 'connect.php';

// Ambil data dari database
$query = $pdo->prepare("SELECT *, DATE_FORMAT(created_at, '%Y-%m-%d') AS date, DATE_FORMAT(created_at, '%H:%i') AS time FROM posts");
$query->execute();
$posts = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda</title>
    <!-- Link ke Font Awesome untuk ikon profesional -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Beranda.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<style>
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
</style>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <a href="Beranda.php"><i class="icon fas fa-home"></i></a>
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
    <div class="container my-4">
        <h2 class="mb-4 text-center">Post Page</h2>
        <a href="Beranda.php" class="text-sm text-muted">Back</a>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            <!-- Loop untuk 9 card -->
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
                            <h5 class="card-title mb-3 fw-bold"><?= htmlspecialchars($post['title']); ?>4</h5>
                            <p class="card-text text-muted">
                                <?= nl2br(htmlspecialchars($post['content'])); ?>
                            </p>
                        </div>
                        <!-- Bagian Bawah: Footer -->
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <div class="text-muted small">736 People Saw</div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-save2 me-3" style="font-size: 1.2rem; cursor: pointer;"></i>
                                <i class="bi bi-share" style="font-size: 1.2rem; cursor: pointer;"></i>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <a href="edit_postingan.php?id=<?= $post['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="delete_postingan.php?id=<?= $post['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>


    <!-- Tambahkan Bootstrap dan JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script untuk mengubah warna ikon like menjadi merah
        document.querySelectorAll('.like-icon').forEach(icon => {
            icon.addEventListener('click', function() {
                // Toggle class "liked" untuk ikon yang diklik
                this.classList.toggle('bi-heart');
                this.classList.toggle('bi-heart-fill');
                this.style.color = this.style.color === 'red' ? 'black' : 'red';
            });
        });
    </script>

    </div>

    <!-- Tambahkan Bootstrap dan JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script untuk mengubah warna ikon like menjadi merah
        document.querySelectorAll('.like-icon').forEach(icon => {
            icon.addEventListener('click', function() {
                // Toggle class "liked" untuk ikon yang diklik
                this.classList.toggle('bi-heart');
                this.classList.toggle('bi-heart-fill');
                this.style.color = this.style.color === 'red' ? 'black' : 'red';
            });
        });
    </script>

    </div>

    </div>
    <script src="Beranda.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>