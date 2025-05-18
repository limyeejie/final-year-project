document.querySelectorAll('.faq-question').forEach(item => {
    item.addEventListener('click', () => {
        const parent = item.parentNode;
        
        // Toggle the active class on the clicked item
        parent.classList.toggle('active');
        
        // Close all other faq answers if needed
        document.querySelectorAll('.faq-item').forEach(otherItem => {
            if (otherItem !== parent && otherItem.classList.contains('active')) {
                otherItem.classList.remove('active');
            }
        });
    });
});