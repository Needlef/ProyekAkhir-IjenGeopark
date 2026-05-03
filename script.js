// ==========================================
// 1. LOGIKA DARK MODE (Sudah Anda buat sebelumnya)
// ==========================================
const themeToggle = document.getElementById('theme-toggle');
const darkModeEnabled = localStorage.getItem('darkMode') === 'true';

if (darkModeEnabled) {
    document.body.classList.add('dark-mode');
    themeToggle.textContent = '☀️';
}

themeToggle.addEventListener('click', function() {
    document.body.classList.toggle('dark-mode');
    
    if (document.body.classList.contains('dark-mode')) {
        themeToggle.textContent = '☀️';
        localStorage.setItem('darkMode', 'true');
    } else {
        themeToggle.textContent = '🌙';
        localStorage.setItem('darkMode', 'false');
    }
});


// ==========================================
// 2. LOGIKA FAQ INTERAKTIF (Tugas 3)
// ==========================================
// Menangkap semua tombol pertanyaan FAQ
const daftarPertanyaan = document.querySelectorAll('.faq-question');

// Melakukan perulangan untuk setiap tombol
daftarPertanyaan.forEach(function(tombol) {
    
    // Menambahkan aksi 'klik' pada masing-masing tombol
    tombol.addEventListener('click', function() {
        
        // Mengambil elemen 'faq-answer' yang posisinya persis di bawah tombol yang diklik
        const jawaban = this.nextElementSibling;
        
        // Logika IF-ELSE: Cek apakah jawaban sedang terbuka (memiliki max-height)
        if (jawaban.style.maxHeight) {
            // Jika terbuka, tutup (jadikan null/0)
            jawaban.style.maxHeight = null;
        } else {
            // Jika tertutup, buka seukuran konten di dalamnya (scrollHeight)
            jawaban.style.maxHeight = jawaban.scrollHeight + "px";
        }
        
    });
});