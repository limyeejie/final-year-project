//toggle sidebar
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('.sidebar');
    const hamburger = document.querySelector('.sidebar .profile-hamburger');
    const content = document.querySelector('.content');

    hamburger.addEventListener('click', () => {
        sidebar.classList.toggle('expanded');
    });

    document.addEventListener('click', (event) => {
        if (!sidebar.contains(event.target) && !hamburger.contains(event.target)) {
            sidebar.classList.remove('expanded');
        }
    });
});

// Get the modal element
var modal = document.getElementById("editModal");

// Get the close button
var closeBtn = modal.querySelector(".close");

// Add click event listener to the close button
closeBtn.addEventListener("click", function() {
    modal.style.display = "none";
});

// Optionally close the modal if the user clicks outside the modal content
window.addEventListener("click", function(event) {
    if (event.target === modal) {
        modal.style.display = "none";
    }
});

// Add an event listener to the save button
document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('.save-btn').addEventListener('click', updatePreferences);
});

function updatePreferences() {
    const alertChecked = document.querySelector('.event-alerts-toggle').checked ? 1 : 0;
    const newsChecked = document.querySelector('.news-announcements-toggle').checked ? 1 : 0;
    const recommendToggle = document.querySelector('.personalized-events-toggle');
    const recommendChecked = recommendToggle ? (recommendToggle.checked ? 1 : 0) : 0;

    const preferences = {
        alert: alertChecked,
        news: newsChecked,
        recommend: recommendChecked
    };

    console.log('Data being sent:', preferences);

    fetch('update_preferences.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(preferences),
        credentials: 'include'
    })
    .then(async response => {
        const text = await response.text(); // Get response as text first
        console.log('Raw response:', text); // Log the raw response
        
        try {
            return JSON.parse(text); // Try to parse it as JSON
        } catch (e) {
            console.error('JSON Parse Error:', e);
            console.error('Response text:', text);
            throw new Error('Invalid JSON response from server');
        }
    })
    .then(data => {
        console.log('Server response:', data);
        if (data.success) {
            //alert('Preferences updated successfully! Please refresh your page!');    
            const redirectUrl = 'profile.php';
            showSuccessModal(redirectUrl, 'Preferences updated successfully!');      
            loadProfileSections();        
            loadSidebar();            
        } else {
            alert(data.message || 'Failed to update preferences.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating preferences. Please try again.');
    });
}

function loadProfileSections() { 
    fetch('get_profile_sections.php', { 
        method: 'GET', 
        headers: { 
            'Content-Type': 'application/json' 
        } 
    }) 
    .then(response => response.text()) 
    .then(html => { 
        document.getElementById('content').innerHTML = html; 
    }) 
    .catch(error => { 
        console.error('Error loading profile sections:', error); 
    }); 
}

function loadSidebar() { 
    fetch('get_profile_sidebar.php', { 
        method: 'GET', 
        headers: { 
            'Content-Type': 'application/json' 
        } 
    }) 
    .then(response => response.text()) 
    .then(html => { 
        document.getElementById('sidebar').innerHTML = html; 
    }) 
    .catch(error => { 
        console.error('Error loading sidebar:', error); 
    }); 
}

document.addEventListener('DOMContentLoaded', () => {
    loadProfileSections();
    loadSidebar();
});


// My reward section
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.download-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default behavior
            const badgeId = this.parentElement.id; // Get the badge ID
            downloadBadge(badgeId);
            console.log('Button clicked:', badgeId); // Log badge ID for debugging
            // Redirect to generate_badge.php
            window.location.href = `generate_badge.php?badge_id=${badgeId}`;
        });
    });
});

function downloadBadge(badgeId) {
    let badgeURL = '';
  
    switch (badgeId) {
      case 'first-event':
        badgeURL = '../images/profile/cert_bronze.png';
        break;
      case 'community-helper':
        badgeURL = '../images/profile/cert_sliver.png';
        break;
      case 'event-leader':
        badgeURL = '../images/profile/cert_gold.png';
        break;
    }
  
    if (badgeURL) {
      const link = document.createElement('a');
      link.href = badgeURL;
      link.download = `${badgeId}-badge.png`;
      link.click();
    }
}

// Function to show loading state
function showLoading() {
    const activityList = document.getElementById("activity-list");
    activityList.innerHTML = `
        <div style="width: 100%; display: flex; justify-content: center; padding: 20px;">
            <div class="loading-spinner"></div>
        </div>
    `;
}

// Function to fetch and display recommended events
async function fetchRecommendedActivities() {
    showLoading();
    
    try {
        const response = await fetch('fetch_user_history.php', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Cache-Control': 'no-cache'
            },
            credentials: 'same-origin'
        });

        if (response.status === 401) {
            const activityList = document.getElementById("activity-list");
            activityList.innerHTML = `
                <div class="error-message" style="text-align: center; padding: 20px;">
                    <p>Please log in to view recommendations</p>
                    <a href="login.php" class="join-btn">Log In</a>
                </div>`;
            return;
        }

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const events = await response.json();
        displayRecommendedActivities(events);

    } catch (error) {
        console.error('Error fetching recommendations:', error);
        const activityList = document.getElementById("activity-list");
        activityList.innerHTML = `
            <div class="error-message" style="text-align: center; padding: 20px;">
                <p>Failed to load recommendations. Please try again later.</p>
                <button onclick="fetchRecommendedActivities()" class="join-btn">
                    Retry
                </button>
            </div>`;
    }
}

// Function to display recommended activities
function displayRecommendedActivities(events) {
    const activityList = document.getElementById("activity-list");
    activityList.innerHTML = "";

    if (!Array.isArray(events) || !events.length) {
        activityList.innerHTML = `
            <div style="width: 100%; text-align: center; padding: 20px; color: #666;">
                <p>No upcoming events available at this time.</p>
                <small>Check back later for new activities!</small>
            </div>`;
        return;
    }

    events.forEach(event => {
        const eventCard = document.createElement("div");
        eventCard.classList.add("activity-card");

        // Format date
        const eventDate = new Date(event.date);
        const formattedDate = eventDate.toLocaleDateString('en-US', {
            weekday: 'short',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });

        // Update eventCard innerHTML
        eventCard.innerHTML = `
            <h4>${sanitizeHTML(event.title)}</h4>
            <p><strong>Date:</strong><br> ${formattedDate}</p>
            <p><strong>Location:</strong><br> ${sanitizeHTML(event.location)}</p>
            <button class="join-btn" 
                    onclick="redirectToEvent(${event.eventId})">
                Join Event
            </button>
        `;

        activityList.appendChild(eventCard);
    });
}

// Function to handle redirection to the event details page - this part have problem
function redirectToEvent(eventId) { 
    console.log('Redirecting to event ID:', eventId); 
    // Debugging output 
    const url = `../templates/event_details.php?eventId=${eventId}`; 
    console.log('Redirect URL:', url); 
    // Debugging output 
    window.location.href = url;
}

// Helper function to sanitize HTML and prevent XSS
function sanitizeHTML(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

// Add loading spinner styles
const style = document.createElement('style');
style.textContent = `
    .loading-spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #eaf1f8;
        border-top: 4px solid #53a5c2;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .error-message {
        background-color: #fff3f3;
        border: 1px solid #ffcdd2;
        border-radius: 4px;
        padding: 15px;
        margin: 10px 0;
    }
`;
document.head.appendChild(style);

// Initialize the recommendations on page load
document.addEventListener('DOMContentLoaded', fetchRecommendedActivities);

// Add automatic refresh every 5 minutes
let refreshInterval = setInterval(fetchRecommendedActivities, 300000);

//Load Event

document.addEventListener('DOMContentLoaded', () => {
//loadEventOverview();
loadPastEvents();
loadEventOverview();
loadUpcomingEvents();
feedbackSubmission();
loadEventSummaryOrganizer();
loadEventSummaryStudent();
loadNotifications();
loadNotificationCount();
const markAsReadButton = document.getElementById('mark-as-read');
markAsReadButton.addEventListener('click', markAllsRead);
setInterval(loadNotifications, 5000);  
setInterval(loadNotificationCount, 5000);  
});

function loadNotificationCount() {
    fetch('get_notification_count.php')
        .then(response => response.json())
        .then(data => {
            if (data.count !== undefined) {
                const notificationCount = document.getElementById('num-of-notif');
                notificationCount.textContent = data.count;
            } else {
                console.log("Error fetching notification count.");
            }
        })
        .catch(error => {
            console.error("Error fetching notification count:", error);
        });
}

function markAllsRead() {

    fetch('mark_all_as_read.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ action: 'mark_all_as_read' })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadNotificationCount();
            // Optionally hide or update the notification section
            document.querySelector('.notifications .main').innerHTML = '';  // Clear notifications
            console.log("All notifications marked as read.");
        } else {
            console.error("Error marking notifications as read.");
        }
    })
    .catch(error => {
        console.error("Error:", error);
    });

}


function loadNotifications() {
    fetch('get_notifications.php')
        .then(response => response.json())
        .then(data => {
            if (data.notifications && data.notifications.length > 0) {
                const notificationContainer = document.querySelector('.notifications .main');
                notificationContainer.innerHTML = "";
                

                data.notifications.forEach(notification => {
                    const notificationCard = document.createElement('div');
                    notificationCard.classList.add('notificationCard', 'unread');
                    notificationCard.innerHTML = `
                    <img src="../images/profile/profile.png" alt="photo">
                    <div class="description">
                        <p>${notification.message}</p>
                        <p id="notif-time">${notification.time}</p>
                    </div>
                `;
                notificationContainer.prepend(notificationCard);
                });

                loadNotificationCount();

            } else {
                console.log('No new notifications');
            }
        })
        .catch(error => {
            console.error('Error fetching notifications:', error);
        });
}

function loadEventSummaryStudent() {
    fetch('fetch_dashboard_student.php') 
        .then(response => response.json())
        .then(data => {
            // Set Total Events Attended
            document.getElementById('totalAttended').textContent = data.totalAttended;
            document.getElementById('totalVolunteered').textContent = data.totalVolunteered;
            document.getElementById('totalParticipated').textContent = data.totalParticipated;


            // Set Upcoming Event Counts by Category
            document.getElementById('skill-count').textContent = data.upcomingCounts['Skills Sharing Session'] || 0;
            document.getElementById('clean-count').textContent = data.upcomingCounts['Community Clean-Up'] || 0;
            document.getElementById('charity-count').textContent = data.upcomingCounts['Charity Event'] || 0;

            // Set Total Upcoming Events
            document.getElementById('total-upcoming').textContent = data.totalUpcoming;
        })
        .catch(error => console.error('Error fetching data:', error));
}

function loadEventSummaryOrganizer() {
    fetch('fetch_dashboard_organizer.php') 
        .then(response => response.json())
        .then(data => {
            // Set Total Events Attended
            document.getElementById('totalEvents').textContent = data.totalEvents;
            document.getElementById('totalVolunteeringEvents').textContent = data.total_volunteering_events;
            document.getElementById('totalParticipated').textContent = data.totalParticipated;
            document.getElementById('totalVolunteered').textContent = data.totalVolunteered;


            // Set Upcoming Event Counts by Category
            document.getElementById('skill-count').textContent = data.upcomingCounts['Skills Sharing Session'] || 0;
            document.getElementById('clean-count').textContent = data.upcomingCounts['Community Clean-Up'] || 0;
            document.getElementById('charity-count').textContent = data.upcomingCounts['Charity Event'] || 0;

            // Set Total Upcoming Events
            document.getElementById('total-upcoming').textContent = data.totalUpcoming;
        })
        .catch(error => console.error('Error fetching data:', error));
}


function feedbackSubmission() {
    const submitButton = document.querySelector('.submit');
    submitButton.addEventListener('click', () => {
        const rating = document.querySelector('input[name="rating"]').value;
        const comment = document.getElementById('comment').value;
        const eventName = document.querySelector('.event-name').textContent;
        const eventId = document.querySelector('.event-id').value;

        if (!rating || !comment) {
            alert("Please provide both a rating and a comment.");
            return;
        }

        const feedbackData = {
            event_name: eventName,
            event_id: eventId,
            rating: rating,
            comment: comment
        };


        fetch('submit_feedback.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(feedbackData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const redirectUrl = 'profile.php';
                showSuccessModal(redirectUrl, 'Feedback submitted successfully');
                document.querySelector('input[name="rating"]').value = '';
                document.getElementById('comment').value = '';

                document.getElementById('feedbackModal').style.display = 'none';
            } else {
                alert("There was an error submitting your feedback. Please try again.");
            }
        })
        .catch(error => console.error('Error:', error));
    });

    const allStar = document.querySelectorAll('.rating .star');
    const ratingValue = document.querySelector('.rating input');
    // Function to open the modal


    // Handle star rating
    allStar.forEach((item, idx) => {
        item.addEventListener('click', function() {
            let click = 0;
            ratingValue.value = idx + 1;
            allStar.forEach(i => {
                i.classList.replace('bxs-star', 'bx-star');
                i.classList.remove('active');
            });
            for (let i = 0; i < allStar.length; i++) {
                if (i <= idx) {
                    allStar[i].classList.replace('bx-star', 'bxs-star');
                    allStar[i].classList.add('active');
                } else {
                    allStar[i].computedStyleMap.setProperty('--i', click);
                    click++;
                }
            }
        });
    });
    // Clear stars when the cancel button is clicked
    const cancelButton = document.querySelector('.btn.cancel');
    const textarea = document.querySelector('textarea'); 
    cancelButton.addEventListener("click", function() {
        ratingValue.value = ''; // clear the rating value
        textarea.value = '';
        allStar.forEach(i => {
            i.classList.replace('bxs-star', 'bx-star');
            i.classList.remove('active');
        });
    });
}


function loadPastEvents() {
    fetch('fetch_past_events.php')
    .then(response => response.json())
    .then(data => {
        const events = data.events;
        const container = document.getElementById('pastEvtContainer');

        events.forEach(event => {
            const eventCard = document.createElement('div');
            eventCard.classList.add('card-item');

            eventCard.innerHTML = `
                <h2 class="up-event-name">${event.title}</h2>
                <p class="up-date-location">Date:${event.date} <br> Location: ${event.location}</p>
                <button class="feedback-btn" data-event-id="${event.id}">Give Feedback</button>
            `;
            container.appendChild(eventCard);

            const feedbackButton = eventCard.querySelector('.feedback-btn');
            const modal = document.getElementById('feedbackModal');
            feedbackButton.addEventListener('click', () => {
                modal.style.display = 'flex';
                const eventName = eventCard.querySelector('.up-event-name').textContent;
                modal.querySelector('.event-name').textContent = `Feedback for: ${eventName}`;

                //const eventId = feedbackButton.getAttribute('data-event-id');
                const hiddenInput = modal.querySelector('.event-id'); // Ensure you have an input with class "event-id"
                hiddenInput.value = event.id; // Set the value of the hidden input field with the event ID
            });
            const closeModal = document.getElementById('closeModal');
            closeModal.addEventListener('click', () => {
                modal.style.display = 'none';
            });
        });
    })
    .catch(error => console.error('Error Loading Events:', error));
}


function loadFeedbacks() {
    fetch('fetch_feedbacks.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const feedbacks = data.events;
                const container = document.getElementById('feedbacksList');

                if (!container) {
                    console.error('Feedback container not found');
                    return;
                }

                // Apply the CSS class for scrollable design
                container.classList.add('scrollable-container');

                container.innerHTML = ''; // Clear the container

                feedbacks.forEach(event => {
                    const feedbackCard = document.createElement('div');
                    feedbackCard.classList.add('card-item');

                    feedbackCard.innerHTML = `
                        <h2 class="feedback-title">${event.title}</h2>
                        <p class="feedback-date">Date: ${event.date}</p>
                        <p class="feedback-location">Location: ${event.location}</p>
                        <button class="show-feedback-btn">Show Feedback</button>
                    `;

                    container.appendChild(feedbackCard);

                    feedbackCard.querySelector('.show-feedback-btn').addEventListener('click', () => {
                        const popup = document.createElement('div');
                        popup.classList.add('popup');

                        const tableRows = event.feedbacks && event.feedbacks.length > 0
                            ? event.feedbacks.map(feedback => `
                                <tr>
                                    <td><strong>Rating:</strong> ${'★'.repeat(feedback.rating)}</td>
                                    <td><strong>Comment:</strong> ${feedback.comment}</td>
                                </tr>
                            `).join('')
                            : `<tr><td>No feedback available for this event.</td></tr>`;

                        popup.innerHTML = `
                            <div class="popup-content">
                                <h2>Feedback for ${event.title}</h2>
                                <table>${tableRows}</table>
                                <button class="close-btn">Close</button>
                            </div>
                        `;

                        document.body.appendChild(popup);

                        popup.querySelector('.close-btn').addEventListener('click', () => {
                            document.body.removeChild(popup);
                        });
                    });
                });
            } else {
                console.error('Failed to load feedbacks:', data.message);
            }
        })
        .catch(error => console.error('Error Loading Feedbacks:', error));
}

// Safeguard: Load feedbacks after the page is fully loaded to avoid interference
window.addEventListener('load', loadFeedbacks);


function loadUpcomingEvents() {
    fetch('fetch_upcoming_events.php')
    .then(response => response.json())
    .then(data => {
        const events = data.events;
        const container = document.getElementById('upcomingEvtContainer');

        events.forEach(event => {
            const eventCard = document.createElement('div');
            eventCard.classList.add('card-item');

            eventCard.innerHTML = `
                <h2 class="up-event-name">${event.title}</h2>
                <p class="up-date-location">Type: ${event.type} <br> Date: ${event.date} <br> Time: ${event.event_time}</p>
                <button class="view-more-evt-btn">View More</button>
                <button class="pull-out-evt-btn">Withdraw</button>
            `;
            container.appendChild(eventCard);

            const viewMoreButton = eventCard.querySelector('.view-more-evt-btn');
            viewMoreButton.addEventListener('click', () => {
                showEventDetails(event); // Pass the event card to display its details
            });

            const pullOutButton = eventCard.querySelector('.pull-out-evt-btn');
            pullOutButton.addEventListener('click', () => {
                if (event.type === 'Event') {
                    pullOutParticipation(event.id);
                } else if (event.type === 'Volunteer') {
                    pullOutVolunteerApplication(event.id);
                }
            })

        });
    })
    .catch(error => console.error('Error Loading Events:', error));
}


function pullOutParticipation(eventId) {
    fetch(`pull_out_participation.php?event_id=${eventId}`, {
        method: 'DELETE',
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const redirectUrl = 'profile.php';
                showSuccessModal(redirectUrl, 'Withdrawed from the event successfully');
            } else {
                alert('Failed to pull out from the event.');
            }
        })
        .catch(error => console.error('Error pulling out from event:', error));
}

function pullOutVolunteerApplication(volunteerId) {
    fetch(`pull_out_volunteer.php?volunteer_id=${volunteerId}`, {
        method: 'DELETE',   
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const redirectUrl = 'profile.php';
                showSuccessModal(redirectUrl, 'Withdrawed from the volunteer event successfully');
            } else {
                alert('Failed to pull out from the volunteer application.');
            }
        })
        .catch(error => console.error('Error pulling out from volunteer application:', error));
}

function showEventDetails(event) {
    //const eventName = event.querySelector('.up-event-name').innerText;
    //const eventDate = event.querySelector('.up-date-location').innerText
    //const eventEmail = "contact@example.com"; // Replace with actual email if available

    // Set modal content
    document.getElementById('upcomingEventName').innerText = event.title;
    document.getElementById('upcomingEventDate').innerText = `Date: ${event.date}`;
    document.getElementById('upcomingEventLocation').innerText = `Location: ${event.location}`;
    document.getElementById('upcomingEventOrganizerName').innerText = `Organizer Name: ${event.organizer_name}`;
    document.getElementById('upcomingEventOrganizerContact').innerText = `Organizer Contact: ${event.organizer_contact}`;
    document.getElementById('upcomingEventOrganizerNumber').innerText = `Organizer Number: ${event.organizer_number}`;
    document.getElementById('upcomingMaxParticipants').innerText = `Max Participants: ${event.max_participants}`;



    // Display the modal
    document.getElementById('eventDetailModal').style.display = 'flex';

    const eventDetailModal = document.getElementById('eventDetailModal');
    const closeUpcomingModal = document.getElementById('closeUpcomingModal');
    closeUpcomingModal.addEventListener('click', () => {
        eventDetailModal.style.display = 'none';
    });
}

function loadEventOverview() {
    fetch('fetch_event_overview.php')
    .then(response => response.json())
    .then(data => {
        const events = data.events;
        const container = document.getElementById('evtOverviewContainer');

        events.forEach(event => {
            const eventCard = document.createElement('div');
            eventCard.classList.add('card-item');

            eventCard.innerHTML = `
                <h2 class="up-event-name">${event.title}</h2>
                <p class="up-date-location"> Type: ${event.type} <br> Paritipants:${event.count} / ${event.max_participants}</p>
                <button class="view-more-evt-btn">View More</button>
            `;
            container.appendChild(eventCard);

            const viewMoreButton = eventCard.querySelector('.view-more-evt-btn');
            viewMoreButton.addEventListener('click', () => {
                showEventOverviewDetails(event); // Pass the event card to display its details
            });
        });
    })
    .catch(error => console.error('Error Loading Events:', error));
}


function showEventOverviewDetails(event) {

    // Set modal content
    document.getElementById('upcomingEventName').innerText = event.title;
    document.getElementById('upcomingEventDate').innerText = `Date: ${event.date}`;
    document.getElementById('upcomingEventLocation').innerText = `Location: ${event.location}`;
    document.getElementById('upcomingEventOrganizerContact').innerText = `Organizer Contact: ${event.organizer_contact}`;
    document.getElementById('upcomingMaxParticipants').innerText = `Max Participants: ${event.max_participants}`;


    // Display the modal
    document.getElementById('eventDetailModal').style.display = 'flex';

    const eventDetailModal = document.getElementById('eventDetailModal');
    const closeUpcomingModal = document.getElementById('closeUpcomingModal');
    closeUpcomingModal.addEventListener('click', () => {
        eventDetailModal.style.display = 'none';
    });
}

//Modal Popup
document.addEventListener('DOMContentLoaded', () => {
    const editButton = document.querySelector('.edit-btn');
    const modal = document.getElementById('editModal');
    const closeModal = document.querySelector('.modal .close');

    // Open the modal when the edit button is clicked
    editButton.addEventListener('click', () => {
        modal.style.display = 'flex';
    });

    // Close the modal when the close button is clicked

    // Close the modal when clicking outside the modal content
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});

// sidebar hover active
const allSideMenu = document.querySelectorAll('.sidebar .side-menu li a');

allSideMenu.forEach(item=> {
    const li = item.parentElement;

    item.addEventListener('click', function() {
        allSideMenu.forEach(i=> {
            i.parentElement.classList.remove('active');
        })
        li.classList.add('active');
    })
});

// Select all sidebar menu items
const menuItems = document.querySelectorAll('.sidebar .side-menu li a');
//switch content sections
function switchSection(section) {
    document.querySelectorAll('.content .section').forEach(sec => {
        sec.style.display = 'none';
    });
    document.querySelector(`.${section}`).style.display = 'block';
}
menuItems.forEach(item => {
    item.addEventListener('click',function(e) {
        e.preventDefault();

        const section = this.getAttribute('data-section');
        switchSection(section);
        menuItems.forEach(i => i.parentElement.classList.remove('active'));
        this.parentElement.classList.add('active');
    });
});
//show the first section as default
switchSection('dashboard');
//toggle sidebar
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('.sidebar');
    const hamburger = document.querySelector('.sidebar .hamburger');
    const content = document.querySelector('.content');

    hamburger.addEventListener('click', () => {
        sidebar.classList.toggle('expanded');
    });

    document.addEventListener('click', (event) => {
        if (!sidebar.contains(event.target) && !hamburger.contains(event.target)) {
            sidebar.classList.remove('expanded');
        }
    });
});

// Upcoming Event section
document.addEventListener('DOMContentLoaded', function () {
    const upcomingEventSwiper = new Swiper('.upcoming-event .slider-wrapper', {
        loop: true,
        grabCursor: true,
        slidesPerView: 5,
        spaceBetween: 20,

        navigation: {
            nextEl: '.upcoming-swiper-button-next',
            prevEl: '.upcoming-swiper-button-prev',
        },

        breakpoints: {
            0: {
                slidesPerView: 1,
                spaceBetween: 10,
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 10,
            },
            1024: {
                slidesPerView: 5,
                spaceBetween: 20,
            }
        }
    });
});

// Past Event Review section
document.addEventListener('DOMContentLoaded', function () {
    const pastEventSwiper = new Swiper('.past-event-review .slider-wrapper', {
        loop: true,
        grabCursor: true,
        slidesPerView: 5,
        spaceBetween: 20,

        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            dynamicBullets: true
        },

        navigation: {
            nextEl: '.past-swiper-button-next',
            prevEl: '.past-swiper-button-prev',
        },

        breakpoints: {
            0: {
                slidesPerView: 1,
                spaceBetween: 10,
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 10,
            },
            1024: {
                slidesPerView: 5,
                spaceBetween: 20,
            }
        }
    });
});

// cancel btn and reset back to the original first value
document.addEventListener("DOMContentLoaded", () => {

    document.querySelector('.delete-acc-btn').addEventListener('click', (event) => {
        const id = event.target.getAttribute('data-id');
        console.log("User ID:", id);
        fetch('delete_users.php', {
            method: 'POST',
            headers: {'Content-Type':'application/json'},
            body: JSON.stringify({id: id})
        })
        .then(response => {
            if(response.ok) {
                alert("Your account has been deleted.");
                window.location.href = "../templates/gen.php";
            } else {
                alert("There was an issue deleting your account. Please try again.");
            }
        })
        .catch(error => console.error("Error deleting account:", error));
    });


    // Help & Support button
    document.querySelector('.help-btn').addEventListener('click', () => {
        window.location.href = "../templates/faqs.php";
    });
    const originalValues = {};
    document.querySelectorAll('.switcher-input').forEach((checkbox) => {
        originalValues[checkbox.id] = checkbox.checked;
    });
    // Handle cancel btn click to reset fields
    document.querySelector('.cancel-btn').addEventListener('click', () => {
        document.querySelectorAll('.switcher-input').forEach((checkbox) => {
            checkbox.checked = originalValues[checkbox.id];
        });
        alert("Changes have been reset to original values.")
    });

});
