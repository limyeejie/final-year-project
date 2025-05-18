//back to top button
let backToTopButton = document.getElementById('back-to-top');
if (backToTopButton) { // Check if backToTopButton exists
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 100) { // Show backToTopButton after 100px from the top
            backToTopButton.style.display = 'block';
        } else {
            backToTopButton.style.display = 'none';
        }
    });

    backToTopButton.addEventListener('click', function(event) {
        event.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' }); // Smooth scroll to top
    });
}

document.querySelector('header .hamburger').addEventListener('click', () => {
    const nav = document.querySelector('header .nav');
    if (nav) {
        nav.classList.toggle('nav-active');
    }
});
