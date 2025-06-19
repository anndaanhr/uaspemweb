<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

include '../connection.php';

$query = "
    SELECT bh.id, u.username, b.title, bh.borrow_date, bh.return_date
    FROM borrow_history bh
    JOIN users u ON bh.user_id = u.id
    JOIN books b ON bh.book_id = b.id
    ORDER BY bh.borrow_date DESC
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Riwayat Peminjaman</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/table.css">
    <script src="js/sidebar.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar-placeholder"></div>

        <div class="main-content">
            <div class="header">
                <h2>Riwayat Peminjaman</h2>
                <div class="user-info">
                    <img src="https://randomuser.me/api/portraits/men/41.jpg" alt="Admin">
                    <div>
                        <h4><?php echo htmlspecialchars($_SESSION['admin']); ?></h4>
                        <p>Administrator</p>
                    </div>
                </div>
            </div>

            <div class="content">
                <h3 class="section-title">History</h3>
                <div class="table-container">

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Judul Buku</th>
                                <th>Peminjam</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['borrow_date']); ?></td>
                                    <td>
                                        <?php
                                        echo $row['return_date']
                                            ? htmlspecialchars($row['return_date'])
                                            : '<span style="color: #dc2626; font-style: italic;">Belum kembali</span>';
                                        ?>
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