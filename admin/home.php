<?php
session_start();
$username = "";
if (isset($_SESSION['admin'])) {
    // sudah login ,set variable username dengan data yang sudah ada di session
    $username = $_SESSION['admin'];
} else {
    // belum login ,maka redirect ke halaman login
    header('Location: index.php');
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Unila</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/sidebar.js" defer></script>
    <script src="js/addBook.js" defer></script>
    <style>
        /* ========== Alert Notifikasi ========== */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 14px;
            display: none;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar-placeholder"></div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h2>Tambah Buku Baru</h2>
                <div class="user-info">
                    <img src="https://randomuser.me/api/portraits/men/41.jpg" alt="Admin">
                    <div>
                        <h4><?php echo htmlspecialchars($_SESSION['admin']); ?></h4>
                        <p>Administrator</p>
                    </div>
                </div>
            </div>

            <div class="form-container">
                <h1 class="form-title"><i class="fas fa-plus-circle"></i> Form Tambah Detail Buku</h1>

                <!-- Tambahkan elemen untuk menampilkan pesan -->
                <div id="message" class="alert"></div>

                <!-- Tambahkan enctype untuk upload file -->
                <form id="bookForm" enctype="multipart/form-data">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="title">Judul Buku *</label>
                            <input type="text" id="title" name="title" placeholder="Masukkan judul buku" required>
                        </div>

                        <div class="form-group">
                            <label for="author">Penulis *</label>
                            <input type="text" id="author" name="author" placeholder="Nama penulis" required>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="genre">Genre *</label>
                            <select id="genre" name="genre" required>
                                <option value="">Pilih genre</option>
                                <option value="Fiksi">Fiksi</option>
                                <option value="Non-Fiksi">Non-Fiksi</option>
                                <option value="Sains">Sains</option>
                                <option value="Sejarah">Sejarah</option>
                                <option value="Teknologi">Teknologi</option>
                                <option value="Sastra">Sastra</option>
                                <option value="Pendidikan">Pendidikan</option>
                                <option value="Anak-anak">Anak-anak</option>
                                <option value="Romansa">Romansa</option>
                                <option value="Fantasi">Fantasi</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="isbn">ISBN *</label>
                            <input type="text" id="isbn" name="isbn" placeholder="Kode ISBN buku" required>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="published_year">Tahun Terbit *</label>
                            <input type="number" id="published_year" name="published_year" min="1900"
                                max="<?php echo date('Y'); ?>" placeholder="Tahun terbit" required>
                        </div>

                        <div class="form-group">
                            <label for="total_copies">Jumlah Salinan *</label>
                            <input type="number" id="total_copies" name="total_copies" min="1" value="1" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi Buku *</label>
                        <textarea id="description" name="description" placeholder="Deskripsi lengkap tentang buku"
                            required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="cover_image">Sampul Buku</label>
                        <div class="image-upload" id="imageUpload">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Klik untuk mengunggah gambar sampul</p>
                            <span>Format yang didukung: JPG, PNG (Maks. 2MB)</span>
                            <input type="file" id="cover_image" name="cover_image" accept="image/*"
                                style="display: none;">
                        </div>
                        <img id="imagePreview" class="image-preview" src="" alt="Preview gambar">
                    </div>

                    <div class="btn-container">
                        <button type="reset" class="btn btn-outline">Reset Form</button>
                        <button type="submit" class="btn btn-primary">Simpan Buku</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>