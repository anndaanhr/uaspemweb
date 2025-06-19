// ========== MENU TOGGLE ==========
const mobileMenuButton = document.querySelector('.mobile-menu-button');
const mobileMenu = document.querySelector('.mobile-menu');

if (mobileMenuButton && mobileMenu) {
    mobileMenuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
}

// ========== LOGIN/LOGOUT ==========
let loginButtonsNodeList; // Renamed from loginBtns to avoid conflict with parameter if any
let logoutButtonsNodeList; // Renamed from logoutBtns

function handleLogin() {
    if (loginButtonsNodeList) loginButtonsNodeList.forEach(btn => btn.classList.add('hidden'));
    if (logoutButtonsNodeList) logoutButtonsNodeList.forEach(btn => btn.classList.remove('hidden'));
    localStorage.setItem('isLoggedIn', 'true');
}

function handleLogout() {
    if (loginButtonsNodeList) loginButtonsNodeList.forEach(btn => btn.classList.remove('hidden'));
    if (logoutButtonsNodeList) logoutButtonsNodeList.forEach(btn => btn.classList.add('hidden'));
    localStorage.setItem('isLoggedIn', 'false');
}

function initializeAuthUI() {
    loginButtonsNodeList = document.querySelectorAll('.login-btn');
    logoutButtonsNodeList = document.querySelectorAll('.logout-btn');

    if (localStorage.getItem('isLoggedIn') === 'true') {
        if (loginButtonsNodeList) loginButtonsNodeList.forEach(btn => btn.classList.add('hidden'));
        if (logoutButtonsNodeList) logoutButtonsNodeList.forEach(btn => btn.classList.remove('hidden'));
    } else {
        if (loginButtonsNodeList) loginButtonsNodeList.forEach(btn => btn.classList.remove('hidden'));
        if (logoutButtonsNodeList) logoutButtonsNodeList.forEach(btn => btn.classList.add('hidden'));
    }

    if (loginButtonsNodeList) {
        loginButtonsNodeList.forEach(btn => btn.addEventListener('click', handleLogin));
    }
    if (logoutButtonsNodeList) {
        logoutButtonsNodeList.forEach(btn => btn.addEventListener('click', handleLogout));
    }
}

// ========== ANIMASI MENGETIK ==========
const typingElement = document.querySelector('.typing-animation');
if (typingElement) {
    const text = "Favorit Anda";
    let index = 0;

    function type() {
        if (index < text.length) {
            typingElement.textContent = text.slice(0, index + 1);
            index++;
            setTimeout(type, 150);
        } else {
            setTimeout(() => {
                index = 0;
                typingElement.textContent = '';
                type();
            }, 2000);
        }
    }
    type();
}

// ========== DETAIL BUKU ========== 
// const booksData = { ... } // DINONAKTIFKAN, SUDAH PAKAI BACKEND PHP

// ========== BOOK LISTING & FILTERING (books.html) ==========
const bookGridContainer = document.getElementById('book-grid-container');
const searchInput = document.getElementById('search-input');
const genreFilter = document.getElementById('genre-filter');
const statusFilter = document.getElementById('status-filter');
const categoryFilterButtonsContainer = document.getElementById('category-filter-buttons');
const noResultsMessage = document.getElementById('no-results-message');

function getBookKey(book) {
    // Helper to find the original key for a book object (e.g., 'bulan')
    for (const key in booksData) {
        if (booksData[key] === book) {
            return key;
        }
    }
    return null; 
}

function displayBooks(filteredBooks) {
    if (!bookGridContainer) return; // Not on books.html
    bookGridContainer.innerHTML = ''; // Clear existing books

    if (Object.keys(filteredBooks).length === 0) {
        if (noResultsMessage) noResultsMessage.classList.remove('hidden');
        return;
    }
    if (noResultsMessage) noResultsMessage.classList.add('hidden');

    for (const key in filteredBooks) {
        const book = filteredBooks[key];
        const bookCard = `
            <a href="book-details.html?book=${key}" class="block book-card-link" data-genre="${book.genre}" data-status="${book.status}" data-title="${book.title}" data-author="${book.author}">
                <div class="bg-white rounded-lg shadow-md overflow-hidden transform transition hover:scale-105 h-full flex flex-col">
                    <img src="${book.cover}" alt="${book.title}" class="w-full h-64 object-cover">
                    <div class="p-4 flex flex-col flex-grow">
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">${book.title}</h3>
                        <p class="text-sm text-gray-600 mb-2">${book.author}</p>
                        <p class="text-sm text-gray-500 mt-auto">Genre: ${book.genre}</p>
                        <div class="mt-2 flex items-center justify-between">
                            <span class="availability-badge px-2 py-1 rounded-full text-xs font-medium 
                                ${book.status === 'Tersedia' ? 'bg-green-100 text-green-800' : 
                                  book.status === 'Dipesan' ? 'bg-yellow-100 text-yellow-800' : 
                                  'bg-red-100 text-red-800'}">
                                ${book.status}
                            </span>
                            <span class="text-sm text-gray-600">${book.availableCopies}/${book.totalCopies}</span>
                        </div>
                    </div>
                </div>
            </a>
        `;
        bookGridContainer.innerHTML += bookCard;
    }
}

function filterAndDisplayBooks() {
    if (!bookGridContainer) return; // Run only on books.html

    const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
    const selectedGenre = genreFilter ? genreFilter.value : '';
    const selectedStatus = statusFilter ? statusFilter.value : '';

    const filteredBooks = {};
    for (const key in booksData) {
        const book = booksData[key];
        const titleMatch = book.title.toLowerCase().includes(searchTerm);
        const authorMatch = book.author.toLowerCase().includes(searchTerm);
        const genreMatch = selectedGenre === '' || book.genre === selectedGenre;
        const statusMatch = selectedStatus === '' || book.status === selectedStatus;

        if ((titleMatch || authorMatch) && genreMatch && statusMatch) {
            filteredBooks[key] = book;
        }
    }
    displayBooks(filteredBooks);
}

function populateFilters() {
    if (!genreFilter || !categoryFilterButtonsContainer) return; // Not on books.html

    const genres = new Set();
    for (const key in booksData) {
        genres.add(booksData[key].genre);
    }

    // Populate genre dropdown
    genres.forEach(genre => {
        const option = document.createElement('option');
        option.value = genre;
        option.textContent = genre;
        genreFilter.appendChild(option);
    });

    // Populate category filter buttons (skip "Semua Kategori" as it's hardcoded)
    categoryFilterButtonsContainer.innerHTML = '<button class="w-full text-left px-4 py-2 text-gray-700 bg-gray-100 hover:bg-blue-500 hover:text-white rounded-md transition category-filter-btn active-category-filter" data-genre="">Semua Kategori</button>'; // Reset and add default
    genres.forEach(genre => {
        const button = document.createElement('button');
        button.className = 'w-full text-left px-4 py-2 text-gray-700 hover:bg-blue-500 hover:text-white rounded-md transition category-filter-btn';
        button.dataset.genre = genre;
        button.textContent = genre;
        categoryFilterButtonsContainer.appendChild(button);
    });

    // Add event listeners to new category buttons
    document.querySelectorAll('.category-filter-btn').forEach(button => {
        button.addEventListener('click', function() {
            // Update main genre dropdown
            genreFilter.value = this.dataset.genre;
            // Remove active class from all category buttons
            document.querySelectorAll('.category-filter-btn').forEach(btn => btn.classList.remove('active-category-filter', 'bg-blue-500', 'text-white'));
            document.querySelectorAll('.category-filter-btn').forEach(btn => btn.classList.add('bg-gray-100', 'text-gray-700')); // Reset style
            // Add active class to clicked button
            this.classList.add('active-category-filter', 'bg-blue-500', 'text-white');
            this.classList.remove('bg-gray-100', 'text-gray-700');

            filterAndDisplayBooks();
        });
    });
}

function initializeBookPage() {
    if (window.location.pathname.includes('books.html')) {
        populateFilters();
        filterAndDisplayBooks(); // Initial display of all books

        if(searchInput) searchInput.addEventListener('input', filterAndDisplayBooks);
        if(genreFilter) genreFilter.addEventListener('change', () => {
            // Sync category buttons with dropdown
            const currentGenre = genreFilter.value;
            document.querySelectorAll('.category-filter-btn').forEach(btn => {
                btn.classList.remove('active-category-filter', 'bg-blue-500', 'text-white');
                btn.classList.add('bg-gray-100', 'text-gray-700');
                if (btn.dataset.genre === currentGenre) {
                    btn.classList.add('active-category-filter', 'bg-blue-500', 'text-white');
                    btn.classList.remove('bg-gray-100', 'text-gray-700');
                }
            });
            filterAndDisplayBooks();
        });
        if(statusFilter) statusFilter.addEventListener('change', filterAndDisplayBooks);
    }
}

// Call initializers
initializeBookPage();

function loadBookDetails() {
    const params = new URLSearchParams(window.location.search);
    const bookKey = params.get('book');
    const book = booksData[bookKey] || booksData.bulan;

    const el = id => document.getElementById(id);
    if (!el('book-title')) return;

    el('book-title').textContent = book.title;
    el('book-author').textContent = book.author;
    el('book-genre').textContent = book.genre;
    el('book-isbn').textContent = book.isbn;
    el('book-published').textContent = book.published;
    el('book-description').textContent = book.description;
    el('book-cover').src = book.cover;
    el('book-cover').alt = `Sampul ${book.title}`;
    el('book-copies').textContent = `${book.availableCopies}/${book.totalCopies} tersedia`;

    const availability = el('availability-badge');
    availability.textContent = book.status;
    availability.className = 'px-3 py-1 rounded-full text-sm font-medium';

    if (book.status === "Tersedia") {
        availability.classList.add('bg-green-100', 'text-green-800');
    } else if (book.status === "Dipesan") {
        availability.classList.add('bg-yellow-100', 'text-yellow-800');
    } else {
        availability.classList.add('bg-red-100', 'text-red-800');
    }
}
if (window.location.pathname.includes('book-detail')) loadBookDetails();

// ========== PINJAM / BATAL PINJAM ==========
const borrowBtn = document.querySelector('.borrow-btn');
if (borrowBtn) {
    const borrowMessage = document.querySelector('.borrow-message');
    const badge = document.getElementById('availability-badge');

    borrowBtn.addEventListener('click', () => {
        if (localStorage.getItem('isLoggedIn') !== 'true') {
            borrowMessage.textContent = 'Silakan masuk untuk meminjam buku';
            borrowMessage.className = 'borrow-message bg-red-100 text-red-800';
            return;
        }

        const status = badge.textContent;
        if (status === 'Tersedia') {
            badge.textContent = 'Dipesan';
            badge.className = 'px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800';
            borrowMessage.textContent = 'Buku berhasil dipinjam! Silakan ambil dalam 24 jam.';
            borrowMessage.className = 'borrow-message bg-green-100 text-green-800';
            borrowBtn.textContent = 'Batalkan Pesanan';
        } else if (status === 'Dipesan') {
            badge.textContent = 'Tersedia';
            badge.className = 'px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800';
            borrowMessage.textContent = 'Pesanan dibatalkan';
            borrowMessage.className = 'borrow-message bg-green-100 text-green-800';
            borrowBtn.textContent = 'Pinjam Buku';
        } else {
            borrowMessage.textContent = 'Buku ini sedang tidak tersedia.';
            borrowMessage.className = 'borrow-message bg-red-100 text-red-800';
        }
    });
}

// ========== FORM KONTAK ==========
const contactForm = document.getElementById('contact-form');
if (contactForm) {
    const formFeedback = document.getElementById('form-feedback');

    contactForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const subject = document.getElementById('subject').value.trim();
        const message = document.getElementById('message').value.trim();

        if (!name || !email || !subject || !message) {
            formFeedback.textContent = 'Mohon isi semua kolom yang wajib diisi.';
            formFeedback.className = 'text-red-600';
            return;
        }

        formFeedback.textContent = 'Pesan Anda telah berhasil dikirim! Kami akan segera menghubungi Anda.';
        formFeedback.className = 'text-green-600';
        contactForm.reset();
    });
}
