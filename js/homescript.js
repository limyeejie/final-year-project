//Hero Section
const heroSection = document.getElementById('hero-section');

heroSection.addEventListener('mousemove', function(event) {
    const sectionWidth = heroSection.offsetWidth;
    const mouseX = event.clientX;
    const midpoint = sectionWidth / 2;

    if (mouseX < midpoint) {
        heroSection.classList.add('hover-left');
        heroSection.classList.remove('hover-right');
    } else {
        heroSection.classList.add('hover-right');
        heroSection.classList.remove('hover-left');
    }
});

heroSection.addEventListener('mouseleave', function() {
    heroSection.classList.remove('hover-left', 'hover-right');
});

//TESTIMONIAL HOMEPAGE
let testimonialIndex = 0;
const testimonialSlides = document.querySelectorAll('.testimonial');
const totalTestimonialSlides = testimonialSlides.length;

// Function to show the testimonial at a given index
function showTestimonial(index) {
  if (index < 0) {
    testimonialIndex = totalTestimonialSlides - 1; // Go to the last slide
  } else if (index >= totalTestimonialSlides) {
    testimonialIndex = 0; // Wrap back to the first slide
  }

  // Ensure all slides are hidden except the active one
  testimonialSlides.forEach((slide, i) => {
    if (i === testimonialIndex) {
      slide.style.display = 'block'; // Show active slide
    } else {
      slide.style.display = 'none'; // Hide others
    }
  });
}

// Function to move to the previous testimonial
function prevTestimonial() {
  showTestimonial(testimonialIndex -= 1);
}

// Function to move to the next testimonial
function nextTestimonial() {
  showTestimonial(testimonialIndex += 1);
}

// Auto-slide logic
let autoTestimonialSlideInterval = setInterval(() => {
  nextTestimonial();
}, 3000);

// Initialize the slider by showing the first testimonial
showTestimonial(testimonialIndex);


// Just For You AI - DRIVEN
document.addEventListener("DOMContentLoaded", function() {
    fetch("../templates/get_recommendations.php")
    .then(response => response.json())
    .then(data => {
        console.log("Data fetched:", data); // Log the fetched data
        if (data.status === "success") {
            const events = data.data; // Accessing the `data` array directly
            renderEventCards(events); // Pass the array to `renderEventCards`
        } else {
            console.error("Error fetching recommendations:", data.message);
        }
    })
    .catch(error => console.error("Error fetching recommendations:", error));

    function renderEventCards(events) {
        const container = document.getElementById("recommendation-container");
        container.innerHTML = ""; 
    
        // Loop through events and create cards
        events.forEach((event) => {
            const eventCard = document.createElement("div");
            eventCard.classList.add("event-card");
    
            // Add event category and "Recommended for You"
            const category = document.createElement("span");
            category.classList.add("event-category");
            category.textContent = `Recommended For You - ${event.event_category}`;
    
            const title = document.createElement("h3");
            title.textContent = event.title;
    
            const description = document.createElement("p");
            description.textContent = event.description;
    
            const button = document.createElement("button");
            button.textContent = "Show More";
            button.onclick = () => {
                window.location.href = `event_details.php?eventId=${event.id}`;
            };
    
            eventCard.appendChild(category);
            eventCard.appendChild(title);
            eventCard.appendChild(description);
            eventCard.appendChild(button);
            container.appendChild(eventCard);
        });
    
        // Fill missing spots with overlays if less than 3 events
        for (let i = events.length; i < 3; i++) {
            const placeholder = document.createElement("div");
            placeholder.classList.add("event-placeholder");
            placeholder.innerHTML = `
                <p>More events will be recommended as you participate or as new events are added!</p>
            `;
            container.appendChild(placeholder);
        }
    
        container.classList.add('loaded');
    }    
});

