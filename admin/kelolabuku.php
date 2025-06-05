<?php
session_start();
$username = "";
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    header('Location: index.php');
    exit;
}

include '../connection.php'; // Pastikan file koneksi ada

$query = "SELECT id, title, author, genre, published_year FROM books";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Buku | Admin LibUnila</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/table.css">
    <script src="js/sidebar.js" defer></script>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div id="sidebar-placeholder"></div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h2>Kelola Buku</h2>
                <div class="user-info">
                    <img src="https://randomuser.me/api/portraits/men/41.jpg" alt="Admin">
                    <div>
                        <h4><?php echo htmlspecialchars($username); ?></h4>
                        <p>Administrator</p>
                    </div>
                </div>
            </div>

            <div class="content">
                <h3 class="section-title">Daftar Buku</h3>

                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Penulis</th>
                                <th>Genre</th>
                                <th>Tahun Terbit</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td><?php echo htmlspecialchars($row['author']); ?></td>
                                    <td><?php echo htmlspecialchars($row['genre']); ?></td>
                                    <td><?php echo htmlspecialchars($row['published_year']); ?></td>
                                    <td>
                                        <a href="editbuku.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                                        <a href="hapus_buku.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Yakin hapus buku ini?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
