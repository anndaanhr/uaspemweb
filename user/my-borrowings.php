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

// Ambil riwayat peminjaman user
$stmt = mysqli_prepare($conn, "
    SELECT bh.*, b.title, b.author, b.cover_image, b.isbn 
    FROM borrow_history bh 
    JOIN books b ON bh.book_id = b.id 
    WHERE bh.user_id = ? 
    ORDER BY bh.borrow_date DESC
");
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$borrowings = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $borrowings[] = $row;
    }
}

// Proses pengembalian buku
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['return_book'])) {
    $borrow_id = intval($_POST['borrow_id']);
    
    // Ambil data peminjaman
    $stmt = mysqli_prepare($conn, "SELECT * FROM borrow_history WHERE id = ? AND user_id = ? AND return_date IS NULL");
    mysqli_stmt_bind_param($stmt, 'ii', $borrow_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $borrow_data = mysqli_fetch_assoc($result);
        
        // Mulai transaksi
        mysqli_begin_transaction($conn);
        
        try {
            // Update return_date
            $stmt = mysqli_prepare($conn, "UPDATE borrow_history SET return_date = CURDATE() WHERE id = ?");
            mysqli_stmt_bind_param($stmt, 'i', $borrow_id);
            mysqli_stmt_execute($stmt);
            
            // Update available_copies
            $stmt = mysqli_prepare($conn, "UPDATE books SET available_copies = available_copies + 1 WHERE id = ?");
            mysqli_stmt_bind_param($stmt, 'i', $borrow_data['book_id']);
            mysqli_stmt_execute($stmt);
            
            mysqli_commit($conn);
            
            $message = 'Pengembalian buku berhasil dicatat!';
            $message_type = 'success';
            
            // Refresh data
            $stmt = mysqli_prepare($conn, "
                SELECT bh.*, b.title, b.author, b.cover_image, b.isbn 
                FROM borrow_history bh 
                JOIN books b ON bh.book_id = b.id 
                WHERE bh.user_id = ? 
                ORDER BY bh.borrow_date DESC
            ");
            mysqli_stmt_bind_param($stmt, 'i', $user_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $borrowings = [];
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $borrowings[] = $row;
                }
            }
            
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $message = 'Terjadi kesalahan saat memproses pengembalian.';
            $message_type = 'error';
        }
    } else {
        $message = 'Data peminjaman tidak ditemukan.';
        $message_type = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman - Perpustakaan Modern</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <?php include 'header.php'; ?>

    <!-- Riwayat Peminjaman -->
    <div class="pt-24 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-8 py-6 bg-blue-600">
                    <h1 class="text-2xl font-bold text-white font-['Playfair_Display']">Riwayat Peminjaman Saya</h1>
                    <p class="text-blue-100 mt-1">Kelola peminjaman buku Anda</p>
                </div>
                
                <div class="p-8">
                    <!-- Pesan -->
                    <?php if ($message): ?>
                    <div class="mb-6 p-4 rounded-md <?= $message_type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700' ?>">
                        <?= htmlspecialchars($message) ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (count($borrowings) > 0): ?>
                    <!-- Statistik Peminjaman -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <?php
                        $total_borrowings = count($borrowings);
                        $active_borrowings = count(array_filter($borrowings, function($b) { return $b['return_date'] === null; }));
                        $completed_borrowings = $total_borrowings - $active_borrowings;
                        ?>
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-blue-900">Total Peminjaman</h3>
                            <p class="text-3xl font-bold text-blue-600"><?= $total_borrowings ?></p>
                        </div>
                        <div class="bg-green-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-green-900">Sedang Dipinjam</h3>
                            <p class="text-3xl font-bold text-green-600"><?= $active_borrowings ?></p>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900">Telah Dikembalikan</h3>
                            <p class="text-3xl font-bold text-gray-600"><?= $completed_borrowings ?></p>
                        </div>
                    </div>
                    
                    <!-- Daftar Peminjaman -->
                    <div class="space-y-6">
                        <?php foreach ($borrowings as $borrow): ?>
                        <div class="border border-gray-200 rounded-lg p-6 <?= $borrow['return_date'] ? 'bg-gray-50' : 'bg-white' ?>">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <!-- Cover Buku -->
                                <div class="col-span-1">
                                    <img src="../images/<?= htmlspecialchars($borrow['cover_image']) ?>" 
                                         alt="<?= htmlspecialchars($borrow['title']) ?>" 
                                         class="w-full h-48 object-cover rounded-lg shadow-md">
                                </div>
                                
                                <!-- Informasi Buku -->
                                <div class="col-span-2">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                        <?= htmlspecialchars($borrow['title']) ?>
                                    </h3>
                                    <p class="text-gray-600 mb-2">oleh <?= htmlspecialchars($borrow['author']) ?></p>
                                    <p class="text-sm text-gray-500 mb-4">ISBN: <?= htmlspecialchars($borrow['isbn']) ?></p>
                                    
                                    <div class="space-y-2">
                                        <div class="flex items-center">
                                            <span class="text-sm text-gray-600 w-32">Tanggal Pinjam:</span>
                                            <span class="text-sm font-medium"><?= date('d/m/Y', strtotime($borrow['borrow_date'])) ?></span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="text-sm text-gray-600 w-32">Tanggal Kembali:</span>
                                            <span class="text-sm font-medium">
                                                <?= $borrow['return_date'] ? date('d/m/Y', strtotime($borrow['return_date'])) : 'Belum dikembalikan' ?>
                                            </span>
                                        </div>
                                        <?php if (!$borrow['return_date']): ?>
                                        <div class="flex items-center">
                                            <span class="text-sm text-gray-600 w-32">Batas Waktu:</span>
                                            <?php
                                            $due_date = date('Y-m-d', strtotime($borrow['borrow_date'] . ' +14 days'));
                                            $is_overdue = strtotime($due_date) < strtotime(date('Y-m-d'));
                                            ?>
                                            <span class="text-sm font-medium <?= $is_overdue ? 'text-red-600' : 'text-green-600' ?>">
                                                <?= date('d/m/Y', strtotime($due_date)) ?>
                                                <?= $is_overdue ? ' (Terlambat)' : '' ?>
                                            </span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Status dan Aksi -->
                                <div class="col-span-1 flex flex-col justify-between">
                                    <div class="mb-4">
                                        <?php if ($borrow['return_date']): ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                            Dikembalikan
                                        </span>
                                        <?php else: ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            Sedang Dipinjam
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <a href="book-details.php?id=<?= $borrow['book_id'] ?>" 
                                           class="block w-full text-center px-4 py-2 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-50 transition text-sm">
                                            Lihat Detail
                                        </a>
                                        
                                        <?php if (!$borrow['return_date']): ?>
                                        <form method="POST" action="" class="w-full">
                                            <input type="hidden" name="borrow_id" value="<?= $borrow['id'] ?>">
                                            <button type="submit" name="return_book" 
                                                    onclick="return confirm('Apakah Anda yakin ingin mengembalikan buku ini?')"
                                                    class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition text-sm">
                                                Kembalikan Buku
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php else: ?>
                    <!-- Jika belum ada peminjaman -->
                    <div class="text-center py-12">
                        <div class="text-6xl text-gray-400 mb-4">📚</div>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-2">Belum Ada Peminjaman</h2>
                        <p class="text-gray-600 mb-6">Anda belum meminjam buku apapun. Mulai jelajahi koleksi kami!</p>
                        <a href="books.php" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            Jelajahi Buku
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