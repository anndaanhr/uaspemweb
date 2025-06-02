document.addEventListener("DOMContentLoaded", function () {
    fetch("header.html")
        .then(response => response.text())
        .then(data => {
            document.getElementById("header-container").innerHTML = data;

            // Pindahkan kode toggle menu ke sini
            const menuButton = document.querySelector('.mobile-menu-button');
            const mobileMenu = document.querySelector('.mobile-menu');

            menuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        });
});

document.addEventListener("DOMContentLoaded", function () {
    fetch("footer.html")
        .then(response => response.text())
        .then(data => {
            document.getElementById("footer-placeholder").innerHTML = data;
        });
});
