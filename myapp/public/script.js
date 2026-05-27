//Dark mode
const themeToggle = document.getElementById('theme-toggle');
const themeIcon = themeToggle ? themeToggle.querySelector('img') : null;
const darkModeEnabled = localStorage.getItem('darkMode') === 'true';

if (themeToggle && themeIcon) {
    const sunIconPath = themeToggle.getAttribute('data-sun-icon');
    const moonIconPath = themeToggle.getAttribute('data-moon-icon');

    if (darkModeEnabled) {
        document.body.classList.add('dark-mode');
        themeIcon.src = moonIconPath;
    }

    themeToggle.addEventListener('click', function() {
        document.body.classList.toggle('dark-mode');
        
        if (document.body.classList.contains('dark-mode')) {
            themeIcon.src = moonIconPath; 
            localStorage.setItem('darkMode', 'true');
        } else {
            themeIcon.src = sunIconPath;
            localStorage.setItem('darkMode', 'false');
        }
    });
}


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

// AJAX Refresh Artikel
const btnRefreshArtikel = document.getElementById('btn-refresh-artikel');
const artikelContainer = document.getElementById('artikel-container');

if (btnRefreshArtikel && artikelContainer) {
    btnRefreshArtikel.addEventListener('click', function() {
        btnRefreshArtikel.classList.add('spinning');
        btnRefreshArtikel.disabled = true;

        fetch('/ajax/artikel')
            .then(response => response.text())
            .then(html => {
                artikelContainer.innerHTML = html;
                btnRefreshArtikel.classList.remove('spinning');
                btnRefreshArtikel.disabled = false;
            })
            .catch(error => {
                console.error('Error fetching articles:', error);
                btnRefreshArtikel.classList.remove('spinning');
                btnRefreshArtikel.disabled = false;
                alert('Gagal memuat artikel.');
            });
    });
}