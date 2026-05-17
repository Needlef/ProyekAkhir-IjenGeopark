//Dark mode
const themeToggle = document.getElementById('theme-toggle');
const darkModeEnabled = localStorage.getItem('darkMode') === 'true';

if (darkModeEnabled) {
    document.body.classList.add('dark-mode');
    themeToggle.textContent = '🌙';
}

themeToggle.addEventListener('click', function() {
    document.body.classList.toggle('dark-mode');
    
    if (document.body.classList.contains('dark-mode')) {
        themeToggle.textContent = '🌙'; 
        localStorage.setItem('darkMode', 'true');
    } else {
        themeToggle.textContent = '☀️';
        localStorage.setItem('darkMode', 'false');
    }
});


//FAQ
const daftarPertanyaan = document.querySelectorAll('.faq-question');

daftarPertanyaan.forEach(function(tombol) {
    
    tombol.addEventListener('click', function() {
        
        const jawaban = this.nextElementSibling;
        
        if (jawaban.style.maxHeight) {
            jawaban.style.maxHeight = null;
        } else {
            jawaban.style.maxHeight = jawaban.scrollHeight + "px";
        }
        
    });
});