document.addEventListener('DOMContentLoaded', function() {
    console.log('addBook.js loaded'); // Debugging
    
    // Pastikan elemen ada sebelum digunakan
    const imageUpload = document.getElementById('imageUpload');
    const coverImageInput = document.getElementById('cover_image');
    const imagePreview = document.getElementById('imagePreview');
    const bookForm = document.getElementById('bookForm');
    const messageDiv = document.getElementById('message');
    
    if (!bookForm) {
        console.error('Form not found!');
        return;
    }
    
    // Handle image upload preview
    if (imageUpload && coverImageInput) {
        imageUpload.addEventListener('click', () => {
            coverImageInput.click();
        });
        
        coverImageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    if (imagePreview) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                        imageUpload.style.display = 'none';
                    }
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
    
    // Form submission handling
    bookForm.addEventListener('submit', function(e) {
        e.preventDefault();
        console.log('Form submitted'); // Debugging
        
        // Validasi form
        if (!validateForm()) {
            return;
        }
        
        // Tampilkan loading state
        const submitBtn = bookForm.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        submitBtn.disabled = true;
        
        // Kirim data ke server
        sendFormData(new FormData(bookForm))
            .then(data => {
                if (data.success) {
                    showMessage(data.message, 'success');
                    // Reset form
                    bookForm.reset();
                    // Reset image preview
                    if (imagePreview) {
                        imagePreview.style.display = 'none';
                    }
                    if (imageUpload) {
                        imageUpload.style.display = 'flex';
                    }
                } else {
                    showMessage(data.message, 'error');
                }
            })
            .catch(error => {
                showMessage('Terjadi kesalahan: ' + error.message, 'error');
            })
            .finally(() => {
                // Kembalikan ke state semula
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
            });
    });
    
    // Reset form handling
    const resetBtn = bookForm.querySelector('button[type="reset"]');
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            // Reset image preview
            if (imagePreview) {
                imagePreview.style.display = 'none';
            }
            if (imageUpload) {
                imageUpload.style.display = 'flex';
            }
            // Reset file input
            if (coverImageInput) {
                coverImageInput.value = '';
            }
            // Sembunyikan pesan
            if (messageDiv) {
                messageDiv.style.display = 'none';
            }
        });
    }
    
    // Fungsi validasi form
    function validateForm() {
        const title = document.getElementById('title');
        const author = document.getElementById('author');
        const genre = document.getElementById('genre');
        const isbn = document.getElementById('isbn');
        const publishedYear = document.getElementById('published_year');
        const totalCopies = document.getElementById('total_copies');
        const description = document.getElementById('description');
        
        let isValid = true;
        const currentYear = new Date().getFullYear();
        
        // Reset semua error
        resetErrors();
        
        // Validasi field wajib
        if (!title.value.trim()) {
            markError(title, 'Judul buku wajib diisi');
            isValid = false;
        }
        
        if (!author.value.trim()) {
            markError(author, 'Penulis wajib diisi');
            isValid = false;
        }
        
        if (!genre.value) {
            markError(genre, 'Pilih genre buku');
            isValid = false;
        }
        
        if (!isbn.value.trim()) {
            markError(isbn, 'ISBN wajib diisi');
            isValid = false;
        }
        
        if (!publishedYear.value) {
            markError(publishedYear, 'Tahun terbit wajib diisi');
            isValid = false;
        } else if (publishedYear.value < 1900 || publishedYear.value > currentYear) {
            markError(publishedYear, `Tahun terbit harus antara 1900 dan ${currentYear}`);
            isValid = false;
        }
        
        if (!totalCopies.value || totalCopies.value < 1) {
            markError(totalCopies, 'Jumlah salinan minimal 1');
            isValid = false;
        }
        
        if (!description.value.trim()) {
            markError(description, 'Deskripsi buku wajib diisi');
            isValid = false;
        }
        
        if (!isValid) {
            showMessage('Harap perbaiki error di form sebelum mengirim', 'error');
        }
        
        return isValid;
    }
    
    // Fungsi untuk menandai error
    function markError(element, message) {
        if (!element) return;
        
        // Tambahkan border merah
        element.style.borderColor = '#e74c3c';
        
        // Buat elemen pesan error
        let errorElement = element.nextElementSibling;
        if (!errorElement || !errorElement.classList.contains('error-message')) {
            errorElement = document.createElement('div');
            errorElement.className = 'error-message';
            errorElement.style.color = '#e74c3c';
            errorElement.style.fontSize = '0.8rem';
            errorElement.style.marginTop = '5px';
            element.parentNode.insertBefore(errorElement, element.nextSibling);
        }
        
        errorElement.textContent = message;
    }
    
    // Fungsi reset error
    function resetErrors() {
        // Hapus border error
        document.querySelectorAll('input, select, textarea').forEach(el => {
            el.style.borderColor = '';
        });
        
        // Hapus pesan error
        document.querySelectorAll('.error-message').forEach(el => {
            el.remove();
        });
    }
    
    // Fungsi untuk mengirim data form
    async function sendFormData(formData) {
        try {
            const response = await fetch('process_add_book.php', {
                method: 'POST',
                body: formData
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('Error:', error);
            throw error;
        }
    }
    
    // Fungsi untuk menampilkan pesan
    function showMessage(text, type) {
        if (!messageDiv) return;
        
        messageDiv.textContent = text;
        messageDiv.className = `alert alert-${type}`;
        messageDiv.style.display = 'block';
        
        // Sembunyikan pesan setelah 5 detik
        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 5000);
    }
});