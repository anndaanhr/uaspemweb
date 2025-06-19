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

// Cek apakah user sudah login dan sudah meminjam buku ini
$user_already_borrowed = false;
if (isset($_SESSION['username']) && $book) {
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'] ?? null;

    // Jika user_id tidak ada di session, ambil dari database
    if (!$user_id) {
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            $user_id = $user_data['id'];
            $_SESSION['user_id'] = $user_id;
        }
    }

    // Cek apakah user sudah meminjam buku ini dan belum dikembalikan
    if ($user_id) {
        $stmt = mysqli_prepare($conn, "SELECT id FROM borrow_history WHERE user_id = ? AND book_id = ? AND return_date IS NULL");
        mysqli_stmt_bind_param($stmt, 'ii', $user_id, $book_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user_already_borrowed = mysqli_num_rows($result) > 0;
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
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet">
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
                            <div class="aspect-[3/4] w-full max-w-xs mx-auto overflow-hidden">
                                <img id="book-cover" src="../images/<?= htmlspecialchars($book['cover_image']) ?>"
                                    alt="Sampul Buku" class="w-full h-full object-cover rounded-lg shadow-md">
                            </div>
                            <div class="mt-6 space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Status:</span>
                                    <span id="availability-badge"
                                        class="px-3 py-1 rounded-full text-sm font-medium <?= $book['available_copies'] > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= $book['available_copies'] > 0 ? 'Tersedia' : 'Tidak Tersedia' ?>
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Eksemplar:</span>
                                    <span id="book-copies"><?= $book['available_copies'] ?>/<?= $book['total_copies'] ?>
                                        tersedia</span>
                                </div>

                                <!-- Tombol Peminjaman -->
                                <?php if (isset($_SESSION['username'])): ?>
                                    <?php if ($user_already_borrowed): ?>
                                        <div
                                            class="w-full bg-yellow-100 border border-yellow-400 text-yellow-700 py-2 px-4 rounded-md text-center">
                                            Anda sudah meminjam buku ini
                                        </div>
                                        <a href="my-borrowings.php"
                                            class="block w-full bg-blue-600 text-white py-2 text-center rounded-md hover:bg-blue-700 transition">
                                            Lihat Riwayat Peminjaman
                                        </a>
                                    <?php elseif ($book['available_copies'] > 0): ?>
                                        <a href="borrow-form.php?book_id=<?= $book['id'] ?>"
                                            class="block w-full bg-blue-600 text-white py-2 text-center rounded-md hover:bg-blue-700 transition">
                                            Pinjam Buku
                                        </a>
                                    <?php else: ?>
                                        <div
                                            class="w-full bg-red-100 border border-red-400 text-red-700 py-2 px-4 rounded-md text-center">
                                            Buku tidak tersedia
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <a href="login.php"
                                        class="block w-full bg-blue-600 text-white py-2 text-center rounded-md hover:bg-blue-700 transition">
                                        Login untuk Meminjam
                                    </a>
                                <?php endif; ?>

                                <div class="borrow-message hidden mt-4 p-4 rounded-md"></div>
                            </div>
                        </div>

                        <!-- Informasi Buku -->
                        <div class="col-span-2">
                            <h1 id="book-title" class="text-3xl font-bold text-gray-900 mb-4 font-['Playfair_Display']">
                                <?= htmlspecialchars($book['title']) ?></h1>
                            <div class="space-y-4">
                                <div>
                                    <span class="text-gray-600">Penulis:</span>
                                    <span id="book-author"
                                        class="ml-2 font-medium"><?= htmlspecialchars($book['author']) ?></span>
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
                                    <span id="book-published"
                                        class="ml-2"><?= htmlspecialchars($book['published_year']) ?></span>
                                </div>
                                <div>
                                    <h2 class="text-xl font-semibold mb-2">Deskripsi</h2>
                                    <p id="book-description" class="text-gray-600 leading-relaxed">
                                        <?= nl2br(htmlspecialchars($book['description'])) ?>
                                    </p>
                                </div>
                            </div>

                            <!-- Informasi Tambahan -->
                            <div class="mt-8 p-6 bg-gray-50 rounded-lg">
                                <h3 class="text-lg font-semibold mb-4 text-gray-900">Informasi Peminjaman</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-600">Maksimal Peminjaman:</span>
                                        <span class="ml-2 font-medium">14 hari</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Denda Keterlambatan:</span>
                                        <span class="ml-2 font-medium">Rp 1.000/hari</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Kondisi Peminjaman:</span>
                                        <span class="ml-2 font-medium">Harus dalam keadaan baik</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Perpanjangan:</span>
                                        <span class="ml-2 font-medium">Maksimal 1x (7 hari)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigasi Kembali -->
                    <div class="px-8 pb-8">
                        <a href="books.php" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                                </path>
                            </svg>
                            Kembali ke Daftar Buku
                        </a>
                    </div>

                <?php else: ?>
                    <div class="p-12 text-center">
                        <div class="text-6xl text-gray-400 mb-4">📚</div>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-2">Buku Tidak Ditemukan</h2>
                        <p class="text-gray-600 mb-6">Maaf, buku yang Anda cari tidak ditemukan.</p>
                        <a href="books.php"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            Lihat Buku Lainnya
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div id="footer-placeholder"></div>
</body>

</html>