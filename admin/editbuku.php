<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}
include '../connection.php';

$id = $_GET['id'];
$query = "SELECT * FROM books WHERE id = $id";
$result = mysqli_query($conn, $query);
$book = mysqli_fetch_assoc($result);
if (!$book) {
    echo "Buku tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Buku</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/editbuku.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="js/sidebar.js" defer></script>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div id="sidebar-placeholder"></div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h2>Edit Buku</h2>
                <div class="user-info">
                    <img src="https://randomuser.me/api/portraits/men/41.jpg" alt="Admin">
                    <div>
                        <h4><?php echo htmlspecialchars($_SESSION['admin']); ?></h4>
                        <p>Administrator</p>
                    </div>
                </div>
            </div>

            <div class="content">
                <form action="proses_editbuku.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $book['id']; ?>">

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="title">Judul Buku *</label>
                            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="author">Penulis *</label>
                            <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="genre">Genre *</label>
                            <select id="genre" name="genre" required>
                                <?php
                                $genres = ["Fiksi", "Non-Fiksi", "Sains", "Sejarah", "Teknologi", "Sastra", "Pendidikan", "Anak-anak", "Romansa", "Fantasi", "Lainnya"];
                                foreach ($genres as $g) {
                                    $selected = ($book['genre'] === $g) ? "selected" : "";
                                    echo "<option value=\"$g\" $selected>$g</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="isbn">ISBN *</label>
                            <input type="text" id="isbn" name="isbn" value="<?php echo htmlspecialchars($book['isbn']); ?>" required>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="published_year">Tahun Terbit *</label>
                            <input type="number" id="published_year" name="published_year" value="<?php echo $book['published_year']; ?>" min="1900" max="<?php echo date('Y'); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="total_copies">Jumlah Salinan *</label>
                            <input type="number" id="total_copies" name="total_copies" value="<?php echo $book['total_copies']; ?>" min="1" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi Buku *</label>
                        <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($book['description']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="cover_image">Sampul Buku (Kosongkan jika tidak diubah)</label>
                        <input type="file" name="cover_image" accept="image/*">
                        <div class="preview-image">
                            <img src="../images/<?php echo htmlspecialchars($book['cover_image']); ?>" alt="Sampul saat ini" width="120">
                        </div>
                    </div>

                    <div class="btn-container">
                        <button type="submit" class="btn btn-primary">Update Buku</button>
                        <a href="kelolabuku.php" class="btn btn-outline">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
