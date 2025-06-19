<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit;
}
include "../connection.php";
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="search-input" class="block text-sm font-medium text-gray-700 mb-1">Cari Buku
                        (Judul/Penulis)</label>
                    <input type="text" id="search-input" placeholder="Contoh: Bulan, Tere Liye"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                    <label for="genre-filter" class="block text-sm font-medium text-gray-700 mb-1">Filter Genre</label>
                    <select id="genre-filter"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Semua Genre</option>
                        <!-- Opsi genre akan diisi oleh JavaScript -->
                    </select>
                </div>
                <div>
                    <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-1">Filter
                        Status</label>
                    <select id="status-filter"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Semua Status</option>
                        <option value="Tersedia">Tersedia</option>
                        <option value="Dipesan">Dipesan</option>
                        <option value="Tidak Tersedia">Tidak Tersedia</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Kategori / Filter Cepat Genre -->
            <div class="col-span-1">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-semibold mb-4">Kategori Populer</h2>
                    <div id="category-filter-buttons" class="space-y-3">
                        <!-- Tombol kategori akan diisi oleh JavaScript -->
                        <button
                            class="w-full text-left px-4 py-2 text-gray-700 bg-gray-100 hover:bg-blue-500 hover:text-white rounded-md transition category-filter-btn"
                            data-genre="">Semua Kategori</button>
                    </div>
                </div>
            </div>

            <!-- Daftar Buku -->
            <div class="col-span-3">
                <div id="book-grid-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Kartu buku akan diisi oleh JavaScript -->
                    <!-- Contoh struktur kartu buku (akan dihapus dan diganti oleh JS) -->
                    <!-- <a href="book-details.html?book=bulan" class="block book-card-link" data-genre="Fiksi" data-status="Tersedia" data-title="Bulan" data-author="Tere Liye">
                        <div class="bg-white rounded-lg shadow-md overflow-hidden transform transition hover:scale-105">
                            <img src="images/moon.jpg" alt="Bulan oleh Tere Liye" class="w-full h-64 object-cover">
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-900">Bulan</h3>
                                <p class="text-sm text-gray-600">Tere Liye</p>
                                <p class="text-sm text-gray-500 mt-2">Genre: Fiksi</p>
                                <div class="mt-4 flex items-center justify-between">
                                    <span class="availability-badge px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Tersedia</span>
                                    <span class="text-sm text-gray-600">3/5 eksemplar</span>
                                </div>
                            </div>
                        </div>
                    </a> -->
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