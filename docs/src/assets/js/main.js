console.log('OKS Theme Documentation loaded');

function initializeApp() {
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('nav a');
    
    navLinks.forEach(link => {
        const linkPath = link.getAttribute('href');
        if (currentPath.includes(linkPath.replace('./', ''))) {
            link.classList.add('bg-gray-100', 'font-semibold');
        } else {
            link.classList.remove('bg-gray-100', 'font-semibold');
        }
    });

    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuClose = document.getElementById('menu-close');

    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', function() {
            mobileMenu.classList.remove('-translate-x-full');
        });
    }

    if (menuClose && mobileMenu) {
        menuClose.addEventListener('click', function() {
            mobileMenu.classList.add('-translate-x-full');
        });
    }

    if (mobileMenu) {
        mobileMenu.addEventListener('click', function(e) {
            if (e.target === mobileMenu) {
                mobileMenu.classList.add('-translate-x-full');
            }
        });
    }
}

document.addEventListener('includesLoaded', initializeApp);
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('[data-include]')) {
        return;
    }
    initializeApp();
});