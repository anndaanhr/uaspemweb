<?php
session_start();
include '../connection.php';

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

// Ambil data user dari session
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

// Ambil id buku dari URL
$book_id = isset($_GET['book_id']) ? intval($_GET['book_id']) : 0;
$book = null;
if ($book_id > 0) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM books WHERE id = ? AND available_copies > 0");
    mysqli_stmt_bind_param($stmt, 'i', $book_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) > 0) {
        $book = mysqli_fetch_assoc($result);
    }
}

// Proses form submission
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['borrow_book'])) {
    $book_id = intval($_POST['book_id']);
    $borrow_date = $_POST['borrow_date'];
    $return_date = $_POST['return_date'];
    
    // Validasi input
    if (empty($borrow_date) || empty($return_date)) {
        $message = 'Tanggal peminjaman dan pengembalian harus diisi.';
        $message_type = 'error';
    } elseif (strtotime($borrow_date) > strtotime($return_date)) {
        $message = 'Tanggal pengembalian harus setelah tanggal peminjaman.';
        $message_type = 'error';
    } elseif (strtotime($borrow_date) < strtotime(date('Y-m-d'))) {
        $message = 'Tanggal peminjaman tidak boleh kurang dari hari ini.';
        $message_type = 'error';
    } else {
        // Cek apakah buku masih tersedia
        $stmt = mysqli_prepare($conn, "SELECT available_copies FROM books WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $book_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $book_data = mysqli_fetch_assoc($result);
            
            if ($book_data['available_copies'] > 0) {
                // Cek apakah user sudah meminjam buku yang sama dan belum dikembalikan
                $stmt = mysqli_prepare($conn, "SELECT id FROM borrow_history WHERE user_id = ? AND book_id = ? AND return_date IS NULL");
                mysqli_stmt_bind_param($stmt, 'ii', $user_id, $book_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                if (mysqli_num_rows($result) > 0) {
                    $message = 'Anda sudah meminjam buku ini dan belum mengembalikannya.';
                    $message_type = 'error';
                } else {
                    // Mulai transaksi
                    mysqli_begin_transaction($conn);
                    
                    try {
                        // Insert ke borrow_history
                        $stmt = mysqli_prepare($conn, "INSERT INTO borrow_history (user_id, book_id, borrow_date, return_date) VALUES (?, ?, ?, NULL)");
                        mysqli_stmt_bind_param($stmt, 'iis', $user_id, $book_id, $borrow_date);
                        mysqli_stmt_execute($stmt);
                        
                        // Update available_copies
                        $stmt = mysqli_prepare($conn, "UPDATE books SET available_copies = available_copies - 1 WHERE id = ?");
                        mysqli_stmt_bind_param($stmt, 'i', $book_id);
                        mysqli_stmt_execute($stmt);
                        
                        mysqli_commit($conn);
                        
                        $message = 'Peminjaman buku berhasil! Silakan ambil buku di perpustakaan.';
                        $message_type = 'success';
                        
                        // Reset book data untuk menampilkan status terbaru
                        $stmt = mysqli_prepare($conn, "SELECT * FROM books WHERE id = ?");
                        mysqli_stmt_bind_param($stmt, 'i', $book_id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        if ($result && mysqli_num_rows($result) > 0) {
                            $book = mysqli_fetch_assoc($result);
                        }
                        
                    } catch (Exception $e) {
                        mysqli_rollback($conn);
                        $message = 'Terjadi kesalahan saat memproses peminjaman.';
                        $message_type = 'error';
                    }
                }
            } else {
                $message = 'Maaf, buku tidak tersedia untuk dipinjam.';
                $message_type = 'error';
            }
        } else {
            $message = 'Buku tidak ditemukan.';
            $message_type = 'error';
        }
    }
}

// Jika tidak ada book_id atau buku tidak tersedia
if (!$book) {
    $message = 'Buku tidak ditemukan atau tidak tersedia untuk dipinjam.';
    $message_type = 'error';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Peminjaman Buku - Perpustakaan Modern</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <?php include 'header.php'; ?>

    <!-- Form Peminjaman -->
    <div class="pt-24 pb-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-8 py-6 bg-blue-600">
                    <h1 class="text-2xl font-bold text-white font-['Playfair_Display']">Form Peminjaman Buku</h1>
                </div>
                
                <div class="p-8">
                    <!-- Pesan -->
                    <?php if ($message): ?>
                    <div class="mb-6 p-4 rounded-md <?= $message_type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700' ?>">
                        <?= htmlspecialchars($message) ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($book && $book['available_copies'] > 0): ?>
                    <!-- Informasi Buku -->
                    <div class="mb-8 p-6 bg-gray-50 rounded-lg">
                        <h2 class="text-xl font-semibold mb-4 text-gray-900">Informasi Buku</h2>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="col-span-1">
                                <img src="../images/<?= htmlspecialchars($book['cover_image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>" class="w-full rounded-lg shadow-md">
                            </div>
                            <div class="col-span-3">
                                <h3 class="text-2xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($book['title']) ?></h3>
                                <div class="space-y-2">
                                    <p><span class="font-medium text-gray-600">Penulis:</span> <?= htmlspecialchars($book['author']) ?></p>
                                    <p><span class="font-medium text-gray-600">Genre:</span> <?= htmlspecialchars($book['genre']) ?></p>
                                    <p><span class="font-medium text-gray-600">ISBN:</span> <?= htmlspecialchars($book['isbn']) ?></p>
                                    <p><span class="font-medium text-gray-600">Tahun Terbit:</span> <?= htmlspecialchars($book['published_year']) ?></p>
                                    <p><span class="font-medium text-gray-600">Eksemplar Tersedia:</span> <?= $book['available_copies'] ?>/<?= $book['total_copies'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Peminjaman -->
                    <form method="POST" action="" class="space-y-6">
                        <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="borrower_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Peminjam
                                </label>
                                <input type="text" id="borrower_name" value="<?= htmlspecialchars($username) ?>" readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600 cursor-not-allowed">
                            </div>
                            
                            <div>
                                <label for="book_title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Judul Buku
                                </label>
                                <input type="text" id="book_title" value="<?= htmlspecialchars($book['title']) ?>" readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600 cursor-not-allowed">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="borrow_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Peminjaman <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="borrow_date" name="borrow_date" required
                                    min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label for="return_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Pengembalian <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="return_date" name="return_date" required
                                    min="<?= date('Y-m-d', strtotime('+1 day')) ?>" value="<?= date('Y-m-d', strtotime('+14 days')) ?>"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        
                        <!-- Ketentuan Peminjaman -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                            <h3 class="text-lg font-medium text-yellow-800 mb-2">Ketentuan Peminjaman</h3>
                            <ul class="text-sm text-yellow-700 space-y-1">
                                <li>• Maksimal peminjaman adalah 14 hari</li>
                                <li>• Denda keterlambatan Rp 1.000 per hari</li>
                                <li>• Buku harus dikembalikan dalam kondisi baik</li>
                                <li>• Satu user hanya dapat meminjam satu eksemplar buku yang sama</li>
                                <li>• Harap ambil buku di perpustakaan setelah peminjaman dikonfirmasi</li>
                            </ul>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="agree_terms" required
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="agree_terms" class="ml-2 text-sm text-gray-700">
                                Saya setuju dengan ketentuan peminjaman yang berlaku <span class="text-red-500">*</span>
                            </label>
                        </div>
                        
                        <div class="flex justify-between pt-4">
                            <a href="book-details.php?id=<?= $book['id'] ?>" 
                                class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                                Kembali
                            </a>
                            <button type="submit" name="borrow_book"
                                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                Konfirmasi Peminjaman
                            </button>
                        </div>
                    </form>
                    
                    <?php else: ?>
                    <!-- Jika buku tidak tersedia -->
                    <div class="text-center py-12">
                        <div class="text-6xl text-gray-400 mb-4">📚</div>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-2">Buku Tidak Tersedia</h2>
                        <p class="text-gray-600 mb-6">Maaf, buku yang Anda pilih tidak tersedia untuk dipinjam saat ini.</p>
                        <a href="books.php" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            Lihat Buku Lainnya
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div id="footer-placeholder"></div>
</body>
</html>