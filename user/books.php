<?php
session_start();
include "../connection.php";

// Ambil filter dari GET
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$genre = isset($_GET['genre']) ? trim($_GET['genre']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';

// Query dasar
$sql = "SELECT * FROM books WHERE 1";
$params = [];

if ($search !== '') {
    $sql .= " AND (title LIKE ? OR author LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($genre !== '') {
    $sql .= " AND genre = ?";
    $params[] = $genre;
}
if ($status !== '') {
    if ($status === 'Tersedia') {
        $sql .= " AND available_copies > 0";
    } elseif ($status === 'Tidak Tersedia') {
        $sql .= " AND available_copies = 0";
    } elseif ($status === 'Dipesan') {
        $sql .= " AND available_copies < total_copies AND available_copies > 0";
    }
}
$sql .= " ORDER BY id DESC";

$stmt = mysqli_prepare($conn, $sql);
if ($params) {
    $types = str_repeat('s', count($params));
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$books = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $books[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku - Perpustakaan Modern</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="js/main.js" defer></script>
    <script src="js/headerFooter.js" defer></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet">
</head>

<body class="bg-gray-50">
    <!-- Header -->
    <?php include 'header.php'; ?>

    <!-- Header Buku -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-gray-900 text-center font-['Playfair_Display']">Koleksi Buku Kami</h1>
            <p class="mt-4 text-xl text-gray-600 text-center">Jelajahi koleksi buku kami yang beragam dari berbagai
                genre</p>
        </div>
    </div>

    <!-- Kontrol Pencarian dan Filter -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <form method="get" action="" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="search-input" class="block text-sm font-medium text-gray-700 mb-1">Cari Buku (Judul/Penulis)</label>
                    <input type="text" id="search-input" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Contoh: Bulan, Tere Liye"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                    <label for="genre-filter" class="block text-sm font-medium text-gray-700 mb-1">Filter Genre</label>
                    <select id="genre-filter" name="genre"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Semua Genre</option>
                        <?php
                        $genreRes = mysqli_query($conn, "SELECT DISTINCT genre FROM books ORDER BY genre");
                        while ($g = mysqli_fetch_assoc($genreRes)) {
                            $selected = ($genre === $g['genre']) ? 'selected' : '';
                            echo "<option value=\"".htmlspecialchars($g['genre'])."\" $selected>".htmlspecialchars($g['genre'])."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-1">Filter Status</label>
                    <select id="status-filter" name="status"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="" <?= $status === '' ? 'selected' : '' ?>>Semua Status</option>
                        <option value="Tersedia" <?= $status === 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                        <option value="Dipesan" <?= $status === 'Dipesan' ? 'selected' : '' ?>>Dipesan</option>
                        <option value="Tidak Tersedia" <?= $status === 'Tidak Tersedia' ? 'selected' : '' ?>>Tidak Tersedia</option>
                    </select>
                </div>
                <div class="md:col-span-3 flex justify-end mt-4">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Terapkan Filter</button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Daftar Buku -->
            <div class="col-span-4">
                <div id="book-grid-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php if (count($books) > 0): ?>
                        <?php foreach ($books as $book): ?>
                            <a href="book-details.php?id=<?= $book['id'] ?>" class="block book-card-link" data-genre="<?= htmlspecialchars($book['genre']) ?>" data-status="<?= ($book['available_copies'] > 0 ? 'Tersedia' : 'Tidak Tersedia') ?>" data-title="<?= htmlspecialchars($book['title']) ?>" data-author="<?= htmlspecialchars($book['author']) ?>">
                                <div class="bg-white rounded-lg shadow-md overflow-hidden transform transition hover:scale-105">
                                    <img src="../images/<?= htmlspecialchars($book['cover_image']) ?>" alt="<?= htmlspecialchars($book['title']) ?> oleh <?= htmlspecialchars($book['author']) ?>" class="w-full h-64 object-cover">
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($book['title']) ?></h3>
                                        <p class="text-sm text-gray-600"><?= htmlspecialchars($book['author']) ?></p>
                                        <p class="text-sm text-gray-500 mt-2">Genre: <?= htmlspecialchars($book['genre']) ?></p>
                                        <p class="text-sm text-gray-500">Tahun: <?= htmlspecialchars($book['published_year']) ?></p>
                                        <div class="mt-4 flex items-center justify-between">
                                            <span class="availability-badge px-2 py-1 rounded-full text-xs font-medium <?= $book['available_copies'] > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                                <?= $book['available_copies'] > 0 ? 'Tersedia' : 'Tidak Tersedia' ?>
                                            </span>
                                            <span class="text-sm text-gray-600"><?= $book['available_copies'] ?>/<?= $book['total_copies'] ?> eksemplar</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-full text-center py-10">
                            <p class="text-xl text-gray-600">Tidak ada buku yang tersedia.</p>
                        </div>
                    <?php endif; ?>
                </div>
                <div id="no-results-message" class="hidden col-span-full text-center py-10">
                    <p class="text-xl text-gray-600">Tidak ada buku yang cocok dengan kriteria pencarian Anda.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div id="footer-placeholder"></div>
</body>

</html>