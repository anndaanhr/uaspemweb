<header class="bg-white shadow-lg fixed w-full z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo -->
            <a href="dashboard.php" class="text-2xl font-bold text-gray-800 font-['Playfair_Display']">
                Library Unila
            </a>

            <!-- Menu Desktop -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="dashboard.php" class="text-gray-700 hover:text-blue-600 transition">Beranda</a>
                <a href="books.php" class="text-gray-700 hover:text-blue-600 transition">Buku</a>

                <!-- Authenticated User Area -->
                <div class="relative ml-4">
                    <?php if (isset($_SESSION['username'])): ?>
                        <button id="user-menu-button" class="flex items-center gap-2 focus:outline-none">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['username']) ?>&background=0D8ABC&color=fff&rounded=true"
                                alt="User Avatar" class="w-8 h-8 rounded-full border">
                            <span class="text-gray-700 font-medium"><?= htmlspecialchars($_SESSION['username']) ?></span>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="user-dropdown"
                            class="absolute right-0 mt-2 w-32 bg-white border border-gray-200 rounded shadow-md hidden z-50">
                            <a href="logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-50">Keluar</a>
                        </div>
                    <?php else: ?>
                        <a href="index.php"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">Masuk</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button class="mobile-menu-button p-2 rounded-md hover:bg-gray-100 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden hidden mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="dashboard.php"
                    class="block px-3 py-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition">Beranda</a>
                <a href="books.php"
                    class="block px-3 py-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition">Buku</a>
                <div class="mt-4 space-y-2">
                    <?php if (isset($_SESSION['username'])): ?>
                        <div class="flex items-center gap-2 px-3">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['username']) ?>&background=0D8ABC&color=fff&rounded=true"
                                class="w-8 h-8 rounded-full border">
                            <span class="text-gray-700 font-medium"><?= htmlspecialchars($_SESSION['username']) ?></span>
                        </div>
                        <a href="logout.php"
                            class="w-full block text-center border border-blue-600 text-blue-600 px-4 py-2 rounded-md hover:bg-blue-50 transition">Keluar</a>
                    <?php else: ?>
                        <a href="index.php"
                            class="w-full block text-center bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">Masuk</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- JavaScript untuk dropdown -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const button = document.getElementById('user-menu-button');
        const dropdown = document.getElementById('user-dropdown');

        if (button && dropdown) {
            button.addEventListener('click', function () {
                dropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', function (e) {
                if (!button.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        }
    });
</script>