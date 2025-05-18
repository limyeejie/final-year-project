let currentPage = 1;
let totalPages = 10;
let resultsPerPage = 10;

function updateResultsPerPage() {
    const selectElement = document.getElementById('results');
    resultsPerPage = parseInt(selectElement.value);
    currentPage = 1; // Reset to page 1 when results per page change
    loadEvents();
}

function loadEvents() {
    const eventsContainer = document.getElementById('events-container');
    const searchInput = document.getElementById('search-input').value;
    eventsContainer.innerHTML = ''; // Clear the container

    fetch(`fetch_event.php?page=${currentPage}&results=${resultsPerPage}&search=${encodeURIComponent(searchInput)}`)
        .then(response => response.json())
        .then(data => {
            const events = data.events || [];
            totalPages = data.totalPages;

            if (events.length > 0) {
                events.forEach(event => {
                    const eventCard = document.createElement('div');
                    eventCard.className = 'event-card';
                    eventCard.innerHTML = `
                                <div class="event-image">
                                    <img src="data:image/jpeg;base64,${event.image}" alt="Event Image">
                                </div>
                                <div class="event-details">
                                    <h2>${event.title}</h2>
                                    <p>${event.date} &bullet; ${event.location}</p>
                                    <p>${event.description.substr(0, 100)}...</p> <!-- Short description -->
                                    <a href="event_details.php?eventId=${event.id}">
                                        <button class="explore-button">Explore More</button>
                                    </a>
                                </div>`;
                    eventsContainer.appendChild(eventCard);
                });
            } else {
                eventsContainer.innerHTML = '<p>No events found.</p>';
            }

            // Update pagination information
            document.getElementById('current-page').textContent = `Page ${currentPage} of ${totalPages}`;
            document.getElementById('prev-btn').disabled = currentPage === 1;
            document.getElementById('next-btn').disabled = currentPage === totalPages;
        })
        .catch(error => {
            console.error('Error fetching events:', error);
            eventsContainer.innerHTML = '<p>Error loading events.</p>';
        });
}



function nextPage() {
    if (currentPage < totalPages) {
        currentPage++;
        loadEvents();
    }
}

function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        loadEvents();
    }
}

document.addEventListener('DOMContentLoaded', loadEvents); // Initial load