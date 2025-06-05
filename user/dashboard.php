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
    <title>Perpustakaan Modern</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="js/main.js" defer></script>
    <script src="js/headerFooter.js" defer></script>
    <link rel="stylesheet" href="user/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

</head>
<body class="bg-gray-50">
    <!-- Header -->
    <div id="header-container"></div>

    <!-- Bagian Hero -->
    <div class="relative bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl font-['Playfair_Display']">
                            <span class="block">Temukan Bacaan</span>
                            <span class="block text-blue-600 typing-animation">Favorit Anda</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Jelajahi koleksi buku kami yang luas, dari klasik hingga bestseller kontemporer. Perjalanan Anda ke dunia literatur dimulai di sini.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                <a href="books.html" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg md:px-10">
                                    Lihat Koleksi Buku
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <!-- Bagian Buku Unggulan -->
    <section id="books" class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-8 font-['Playfair_Display']">Buku Unggulan</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Kartu Buku 1 -->
                <a href="book-details.html?book=bulan" class="block">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:scale-105 book-card">
                        <img src="images/moon.jpg" alt="Bulan oleh Tere Liye" class="w-full h-64 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900">Bulan</h3>
                            <p class="text-sm text-gray-600">Tere Liye</p>
                            <button class="mt-4 w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition">Pesan Sekarang</button>
                        </div>
                    </div>
                </a>
                <!-- Kartu Buku 2 -->
                <a href="book-details.html?book=hujan" class="block">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:scale-105 book-card">
                        <img src="images/hujan.jpg" alt="Hujan oleh Tere Liye" class="w-full h-64 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900">Hujan</h3>
                            <p class="text-sm text-gray-600">Tere Liye</p>
                            <button class="mt-4 w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition">Pesan Sekarang</button>
                        </div>
                    </div>
                </a>
                <!-- Kartu Buku 3 -->
                <a href="book-details.html?book=matahari" class="block">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:scale-105 book-card">
                        <img src="images/matahari.jpg" alt="Matahari oleh Tere Liye" class="w-full h-64 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900">Matahari</h3>
                            <p class="text-sm text-gray-600">Tere Liye</p>
                            <button class="mt-4 w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition">Pesan Sekarang</button>
                        </div>
                    </div>
                </a>
                <!-- Kartu Buku 4 -->
                <a href="book-details.html?book=crypto" class="block">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:scale-105 book-card">
                        <img src="images/crypto-trading.jpg" alt="Panduan Trading Crypto" class="w-full h-64 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900">Panduan Trading Crypto</h3>
                            <p class="text-sm text-gray-600">Akademi Crypto</p>
                            <button class="mt-4 w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition">Pesan Sekarang</button>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Bagian Layanan -->
    <section id="services" class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-12 font-['Playfair_Display']">Layanan Kami</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-blue-100 rounded-full p-6 w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Peminjaman Buku</h3>
                    <p class="text-gray-600">Akses koleksi buku kami yang beragam untuk semua usia dan minat.</p>
                </div>
                <div class="text-center">
                    <div class="bg-blue-100 rounded-full p-6 w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Program Membaca</h3>
                    <p class="text-gray-600">Bergabunglah dengan klub baca dan program edukasi kami untuk semua usia.</p>
                </div>
                <div class="text-center">
                    <div class="bg-blue-100 rounded-full p-6 w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Sumber Daya Digital</h3>
                    <p class="text-gray-600">Akses e-book, database online, dan materi pembelajaran digital.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <div id="footer-placeholder"></div>
</body>
</html>
