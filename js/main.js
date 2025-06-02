
// ========== MENU TOGGLE ==========
const mobileMenuButton = document.querySelector('.mobile-menu-button');
const mobileMenu = document.querySelector('.mobile-menu');

if (mobileMenuButton && mobileMenu) {
    mobileMenuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
}

// ========== LOGIN/LOGOUT ==========
const loginBtns = document.querySelectorAll('.login-btn');
const logoutBtns = document.querySelectorAll('.logout-btn');

function handleLogin() {
    loginBtns.forEach(btn => btn.classList.add('hidden'));
    logoutBtns.forEach(btn => btn.classList.remove('hidden'));
    localStorage.setItem('isLoggedIn', 'true');
}

function handleLogout() {
    loginBtns.forEach(btn => btn.classList.remove('hidden'));
    logoutBtns.forEach(btn => btn.classList.add('hidden'));
    localStorage.setItem('isLoggedIn', 'false');
}

function checkLoginStatus() {
    if (localStorage.getItem('isLoggedIn') === 'true') {
        handleLogin();
    } else {
        handleLogout();
    }

    loginBtns.forEach(btn => btn.addEventListener('click', handleLogin));
    logoutBtns.forEach(btn => btn.addEventListener('click', handleLogout));
}
checkLoginStatus();

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
const booksData = {
    bulan: {
        title: "Bulan",
        author: "Tere Liye",
        genre: "Fiksi",
        isbn: "978-602-03-3496-3",
        published: "2015",
        description: "\"Bulan\" adalah buku kedua dari seri fantasi remaja karya Tere Liye...",
        cover: "images/moon.jpg",
        availableCopies: 3,
        totalCopies: 5,
        status: "Tersedia"
    },
    hujan: {
        title: "Hujan",
        author: "Tere Liye",
        genre: "Fiksi",
        isbn: "978-602-03-2478-4",
        published: "2016",
        description: "Novel \"Hujan\" mengisahkan tentang Esok dan Lail...",
        cover: "images/hujan.jpg",
        availableCopies: 2,
        totalCopies: 5,
        status: "Dipesan"
    },
    matahari: {
        title: "Matahari",
        author: "Tere Liye",
        genre: "Fiksi",
        isbn: "978-602-03-3211-2",
        published: "2016",
        description: "\"Matahari\" melanjutkan petualangan Raib, Seli, dan Ali...",
        cover: "images/matahari.jpg",
        availableCopies: 0,
        totalCopies: 5,
        status: "Tidak Tersedia"
    },
    crypto: {
        title: "Panduan Trading Crypto",
        author: "Akademi Crypto",
        genre: "Bisnis",
        isbn: "978-123-45-6789-0",
        published: "2023",
        description: "Panduan lengkap bagi pemula tentang trading crypto...",
        cover: "images/crypto-trading.jpg",
        availableCopies: 4,
        totalCopies: 5,
        status: "Tersedia"
    }
};

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
