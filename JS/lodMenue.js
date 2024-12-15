document.addEventListener('DOMContentLoaded', () => {
    // For loading the menu
    fetch('components/menue.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('menu-placeholder').innerHTML = data;

            const currentPage = window.location.pathname.split('/').pop();
            const menuLinks = document.querySelectorAll('.nav-link');

            menuLinks.forEach(link => {
                if (link.getAttribute('href') === currentPage) {
                    link.classList.add('active');
                }
            });
        });
});