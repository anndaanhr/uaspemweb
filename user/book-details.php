<?php
session_start();
include '../connection.php';

// Ambil id buku dari URL
$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$book = null;
if ($book_id > 0) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM books WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $book_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) > 0) {
        $book = mysqli_fetch_assoc($result);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Buku - Perpustakaan Modern</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="js/main.js" defer></script>
    <script src="js/headerFooter.js" defer></script>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50">
   <!-- Header -->
    <?php include 'header.php'; ?>

    <!-- Bagian Detail Buku -->
    <div class="pt-24 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <?php if ($book): ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 p-8">
                    <!-- Gambar Buku -->
                    <div class="col-span-1">
                        <img id="book-cover" src="../images/<?= htmlspecialchars($book['cover_image']) ?>" alt="Sampul Buku" class="w-full rounded-lg shadow-md">
                        <div class="mt-6 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Status:</span>
                                <span id="availability-badge" class="px-3 py-1 rounded-full text-sm font-medium <?= $book['available_copies'] > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= $book['available_copies'] > 0 ? 'Tersedia' : 'Tidak Tersedia' ?>
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Eksemplar:</span>
                                <span id="book-copies"><?= $book['available_copies'] ?>/<?= $book['total_copies'] ?> tersedia</span>
                            </div>
                            <button class="borrow-btn w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition">Pinjam Buku</button>
                            <div class="borrow-message hidden mt-4 p-4 rounded-md"></div>
                        </div>
                    </div>

                    <!-- Informasi Buku -->
                    <div class="col-span-2">
                        <h1 id="book-title" class="text-3xl font-bold text-gray-900 mb-4 font-['Playfair_Display']"><?= htmlspecialchars($book['title']) ?></h1>
                        <div class="space-y-4">
                            <div>
                                <span class="text-gray-600">Penulis:</span>
                                <span id="book-author" class="ml-2 font-medium"><?= htmlspecialchars($book['author']) ?></span>
                            </div>
                            <div>
                                <span class="text-gray-600">Genre:</span>
                                <span id="book-genre" class="ml-2"><?= htmlspecialchars($book['genre']) ?></span>
                            </div>
                            <div>
                                <span class="text-gray-600">ISBN:</span>
                                <span id="book-isbn" class="ml-2"><?= htmlspecialchars($book['isbn']) ?></span>
                            </div>
                            <div>
                                <span class="text-gray-600">Terbit:</span>
                                <span id="book-published" class="ml-2"><?= htmlspecialchars($book['published_year']) ?></span>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold mb-2">Deskripsi</h2>
                                <p id="book-description" class="text-gray-600 leading-relaxed">
                                    <?= nl2br(htmlspecialchars($book['description'])) ?>
                                </p>
                            </div>
                        </div>
                        <!-- Peringkat dan Ulasan (dummy) DIHAPUS -->
                    </div>
                </div>
                <?php else: ?>
                <div class="p-12 text-center text-xl text-gray-600">Buku tidak ditemukan.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div id="footer-placeholder"></div>
</body>
</html> 