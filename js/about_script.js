// Removed the team member image rotator to keep images static.
document.addEventListener("DOMContentLoaded", function() {
    const whyChooseUs = document.querySelector('.why-choose-us');
    
    function animateSection() {
        const sectionPosition = whyChooseUs.getBoundingClientRect().top;
        const screenPosition = window.innerHeight / 1.3;
        
        if (sectionPosition < screenPosition) {
            whyChooseUs.classList.add('animate');
            window.removeEventListener('scroll', animateSection);
        }
    }
    
    window.addEventListener('scroll', animateSection);
});

// Add the 'animate' class when the element is scrolled into view
document.addEventListener("DOMContentLoaded", function() {
    const whyChooseUs = document.querySelector('.why-choose-us');
    
    function animateSection() {
        const sectionPosition = whyChooseUs.getBoundingClientRect().top;
        const screenPosition = window.innerHeight / 1.3;
        
        if (sectionPosition < screenPosition) {
            whyChooseUs.classList.add('animate');
            window.removeEventListener('scroll', animateSection);
        }
    }
    
    window.addEventListener('scroll', animateSection);
});