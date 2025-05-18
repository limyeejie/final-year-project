document.addEventListener('DOMContentLoaded', () => {
    const swiper = new Swiper('.slider-wrapper', {
        loop: true,
        grabCursor: true,
        slidesPerView: 3,
        spaceBetween: 30,

        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            dynamicBullets: true
        },

        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },

        breakpoints: {
            0: {
                slidesPerView: 1,
                spaceBetween: 10,
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            1024: {
                slidesPerView: 3,
                spaceBetween: 30,
            }
        }
    });
});

function navigateToOpportunity(volunteerId) {
    const url = `see_opportunity.php?volunteerId=${encodeURIComponent(volunteerId)}`;
    window.location.href = url;
}

// Testimonials Auto-Slider Script
let slider = document.querySelector('.volunteer-testimonial-slider');
let slides = document.querySelectorAll('.vol-testimonial');
let totalSlides = slides.length;
let index = 0;

function changeSlide() {
    index++;
    if (index === totalSlides) {
        index = 0;
    }
    slider.style.transform = 'translateX(' + (-index * 100) + '%)';
}

// Auto slide every 3 seconds
setInterval(changeSlide, 3000);


const buttons = document.querySelectorAll('.opportunity-btn');
buttons.forEach(button => {
    button.addEventListener('click', () => {
        window.location.href = 'see_opportunity.php'; 
    });
});