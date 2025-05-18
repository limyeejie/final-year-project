document.addEventListener('DOMContentLoaded', () => {

    let currentPage = 1;
    let resultPerPage = 10;
    let totalPages = 1;

    sortVolunteers();
    sortEvents();
    document.getElementById('sort').addEventListener('change', function() {
        sortBoth();
    });

    function sortBoth() {
        const sortBy = document.getElementById('sort').value;
        sortVolunteers(sortBy);
        sortEvents(sortBy);
    }

    window.nextPage = function (sortBy = 'title') {
        if (currentPage < totalPages) {
            currentPage++;
            loadEvents(sortBy);
            loadVolunteers(sortBy);
        }
    };

    window.prevPage = function (sortBy = 'title') {
        if (currentPage > 1) {
            currentPage--;
            loadEvents(sortBy);
            loadVolunteers(sortBy);
        }
    };

    window.updateResultsPerPage = function () {
        const dropdown = document.getElementById('results');
        resultPerPage = parseInt(dropdown.value);
        currentPage = 1;
        loadEvents();
        loadVolunteers();
    }

    document.querySelector('.filter-button').addEventListener('click', loadEvents);
    document.querySelector('.filter-button').addEventListener('click', loadVolunteers);
    
    const volunteersTab = document.getElementById('volunteers-tab');
    const participantsTab = document.getElementById('participants-tab');
    const volunteerSection = document.querySelector('.volunteers-section');
    const participantSection = document.querySelector('.participants-section');     

    const createVolunteerEventBtn = document.getElementById('createVolunteerEventBtn');
    const createParticipantEventBtn = document.getElementById('createParticipantEventBtn');

    const volunteerModal = document.getElementById('volunteerModal');
    const participantModal = document.getElementById('participantModal');
    const volunteerCloseModal = document.getElementById('volunteerCloseModal');
    const participantCloseModal = document.getElementById('participantCloseModal');
    const addRoleBtn = document.getElementById('addRoleBtn');
    const roleContainer = document.getElementById('roleContainer');

    const editMaxParticipants = document.getElementById('editMaxParticipants');
    const editParticipantLabel = document.getElementById('editParticipantLabel');

    const editVolunteerModal = document.getElementById('editVolunteerModal');
    const editParticipantModal = document.getElementById('editParticipantModal');
    const deleteEventModal = document.getElementById('deleteEventModal');
    const viewEventModal = document.getElementById('viewEventModal');

    const deleteVolunteerModal = document.getElementById('deleteVolunteerModal');

    // Buttons to open modals
    const editButtons = document.querySelectorAll('.edit-btn');
    const deleteButtons = document.querySelectorAll('.delete-btn');
    const deleteVolunteerButtons = document.querySelectorAll('.delete-volunteer-btn');
    const viewButtons = document.querySelectorAll('.view-btn');
    
    const closeEditVolunteerModal = document.getElementById('closeEditVolunteerModal');
    const closeEditParticipantModal = document.getElementById('closeEditParticipantModal');
    const closeDeleteModal = document.getElementById('closeDeleteModal');
    const closeViewModal = document.getElementById('closeViewModal');

    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');


    function loadVolunteers(sortBy = 'title') {

        const resultsPerPage = document.getElementById('results').value; // Get results per page
        const searchInput = document.getElementById('search-input').value; // Get search input
    
        fetch(`fetch_volunteers.php?page=${currentPage}&results=${resultsPerPage}&search=${encodeURIComponent(searchInput)}&sort=${sortBy}`)
            .then(response => response.json())
            .then(data => {
                const volunteers = data.volunteers || [];
                totalPages = data.totalPages;
    
                const volunteerTableBody = document.querySelector('.volunteers-section .event-table tbody');
                volunteerTableBody.innerHTML = ''; // Clear previous rows
    
                if (volunteers.length > 0) {
                    volunteers.forEach(volunteer => {
                        const volunteerRow = `
                            <tr>
                                <td><img src="data:image/jpeg;base64,${volunteer.image}" alt="${volunteer.title}" class="event-table-image" style="width: 100px;"></td>
                                <td>${volunteer.title}</td>
                                <td>${volunteer.date}</td>
                                <td>${volunteer.location}</td>
                                <td>${volunteer.max_volunteer}</td>
                                <td>
                                    <a href="?editVolunteerId=${volunteer.id}" class="edit-btn"><i class="fas fa-edit"> edit</i></a>
                                    <button class="delete-btn" data-id="${volunteer.id}"><i class="fas fa-trash-alt"> delete</i></button>
                                    <a href="?viewVolunteerId=${volunteer.id}" class="view-btn"><i class="fas fa-eye"> view</i></a>
                                    <a href="volunteer_list.php?volunteerId=${volunteer.id}" class="list-btn"><i class="fas fa-list"> list</i></a>
                                   </td>
                            </tr>
                        `;
                        volunteerTableBody.innerHTML += volunteerRow; // Add the row to the table body
                    });
                } else {
                    volunteerTableBody.innerHTML = '<tr><td colspan="6">No events found.</td></tr>';
                }
    
                // Update pagination information
                document.getElementById('current-page').textContent = `Page ${currentPage} of ${totalPages}`;
                document.getElementById('prev-btn').disabled = currentPage === 1;
                document.getElementById('next-btn').disabled = currentPage === totalPages;
            })
            .catch(error => {
                console.error('Error fetching volunteers:', error);
                volunteerTableBody.innerHTML = '<tr><td colspan="6">Error loading events.</td></tr>';
            });
    }

    function sortVolunteers(sortBy = 'title') {
        loadVolunteers(sortBy)
    }






    function loadEvents(sortBy = 'title') {
        const resultsPerPage = document.getElementById('results').value; // Get results per page
        const searchInput = document.getElementById('search-input').value; // Get search input
    
        fetch(`fetch_events.php?page=${currentPage}&results=${resultsPerPage}&search=${encodeURIComponent(searchInput)}&sort=${sortBy}`)
            .then(response => response.json())
            .then(data => {
                const events = data.events || [];
                totalPages = data.totalPages;
    
                const participantTableBody = document.querySelector('.participants-section .event-table tbody');
                participantTableBody.innerHTML = ''; // Clear previous rows
    
                if (events.length > 0) {
                    events.forEach(event => {
                        const eventRow = `
                            <tr>
                                <td><img src="data:image/jpeg;base64,${event.image}" alt="${event.title}" class="event-table-image" style="width: 100px;"></td>
                                <td>${event.title}</td>
                                <td>${event.date}</td>
                                <td>${event.location}</td>
                                <td>${event.max_participants}</td>
                                <td>
                                    <a href="?editEventId=${event.id}" class="edit-btn"><i class="fas fa-edit"> edit</i></a>
                                    <button class="delete-btn" data-id="${event.id}"><i class="fas fa-trash-alt"> delete</i></button>
                                    <a href="?viewEventId=${event.id}" class="view-btn"><i class="fas fa-eye"> view</i></a>
                                    <a href="participant_list.php?eventId=${event.id}" class="list-btn"><i class="fas fa-list"> list</i></a>
                                   </td>
                            </tr>
                        `;
                        participantTableBody.innerHTML += eventRow; // Add the row to the table body
                    });
                } else {
                    participantTableBody.innerHTML = '<tr><td colspan="6">No events found.</td></tr>';
                }
    
                // Update pagination information
                updatePagination();
            })
            .catch(error => {
                console.error('Error fetching events:', error);
                participantTableBody.innerHTML = '<tr><td colspan="6">Error loading events.</td></tr>';
            });
    }
    function sortEvents(sortBy = 'title') {

        loadEvents(sortBy)
    }
    
    function nextPage(sortBy = 'title') {
        if (currentPage < totalPages) {
            currentPage++; // Increment the page number
            loadEvents(sortBy); 
            loadVolunteers(sortBy);
        }
    }

    function prevPage(sortBy = 'title') {
        if (currentPage > 1) {
            currentPage--; // Decrement the page number
            loadEvents(sortBy);
            loadVolunteers(sortBy);
        }
    }

    function updatePagination() {
        document.getElementById('current-page').textContent = `Page ${currentPage} of ${totalPages}`;
        document.getElementById('current').textContent = `Page ${currentPage} of ${totalPages}`;
        document.getElementById('prev-btn').disabled = currentPage === 1;
        document.getElementById('next-btn').disabled = currentPage === totalPages;
    }

    let eventToDeleteId = null;

    let volunteerToDeleteId = null;

    // Event delegation for delete button clicks
    document.querySelector('.participants-section .event-table tbody').addEventListener('click', (event) => {
        if (event.target.closest('.delete-btn')) {
            eventToDeleteId = event.target.closest('button').dataset.id; 
            deleteEventModal.style.display = 'flex'; // Show the modal
        }
    });

    document.querySelector('.volunteers-section .event-table tbody').addEventListener('click', (volunteer) => {
        if (volunteer.target.closest('.delete-btn')) {
            volunteerToDeleteId = volunteer.target.closest('button').dataset.id; 
            deleteVolunteerModal.style.display = 'flex'; // Show the modal
        }
    });


    confirmVolunteerDeleteBtn.addEventListener('click', () => {
        fetch(`delete_volunteer.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: volunteerToDeleteId}),
        })
        .then (response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error("Network response was not ok.");
        })
        .then(data=> {
            const redirectUrl = 'management.php';
            showSuccessModal(redirectUrl, 'Volunteer deleted successfully');
            deleteVolunteerModal.style.display = 'none';
            loadVolunteers();
        })
        .catch(error => {
            console.error('Error deleting volunteer:', error);
            alert("Error deleting event. Please try again.");
            deleteVolunteerModal.style.display = 'none';
        });
    });

    // Cancel Deletion
    cancelDeleteBtn.addEventListener('click', () => {
        deleteEventModal.style.display = 'none';
    });


    confirmDeleteBtn.addEventListener('click', () => {
        fetch(`delete_event.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: eventToDeleteId}),
        })
        .then (response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error("Network response was not ok.");
        })
        .then(data=> {
            const redirectUrl = 'management.php';
            showSuccessModal(redirectUrl, 'Event deleted successfully');
            deleteEventModal.style.display = 'none';
            loadEvents();
        })
        .catch(error => {
            console.error('Error deleting event:', error);
            alert("Error deleting event. Please try again.");
            deleteEventModal.style.display = 'none';
        });
    });



    // Tabs switching functionality
    volunteersTab.addEventListener('click', () => {
        volunteersTab.classList.add('active');
        participantsTab.classList.remove('active');
        volunteerSection.style.display = 'block';
        participantSection.style.display = 'none';
        loadVolunteers(currentPage);
    });

    participantsTab.addEventListener('click', () => {
        participantsTab.classList.add('active');
        volunteersTab.classList.remove('active');
        volunteerSection.style.display = 'none';
        participantSection.style.display = 'block';
        loadEvents(currentPage);
    });




    // Open the modal for volunteers
    createVolunteerEventBtn.addEventListener('click', () => {
        // Show volunteer-specific modal
        volunteerModal.style.display = 'flex';
        
    });

    // Open the modal for participants
    createParticipantEventBtn.addEventListener('click', () => {
        // Show participant-specific modal
        participantModal.style.display = 'flex';


    });

    const viewVolunteerModal = document.getElementById('viewVolunteerModal');
    const closeViewVolunteerModal = document.getElementById('closeViewVolunteerModal');

    closeViewVolunteerModal.addEventListener('click', () => {
        viewVolunteerModal.style.display = 'none';

    });

    // Close Modals
    closeEditVolunteerModal.addEventListener('click', () => {
        editVolunteerModal.style.display = 'none';
    });
    
    closeEditParticipantModal.addEventListener('click', () => {
        editParticipantModal.style.display = 'none';

    
    });

    const closeVolunteerDeleteModal = document.getElementById('closeVolunteerDeleteModal');
    const cancelVolunteerDeleteBtn = document.getElementById('cancelVolunteerDeleteBtn');

    cancelVolunteerDeleteBtn.addEventListener('click', () => {
        deleteVolunteerModal.style.display = 'none';
    });


    closeVolunteerDeleteModal.addEventListener('click', () => {
        deleteVolunteerModal.style.display = 'none';

    });
    
    
    closeDeleteModal.addEventListener('click', () => {
        deleteEventModal.style.display = 'none';

    });
    
    closeViewModal.addEventListener('click', () => {
        viewEventModal.style.display = 'none';

    });

    // Close Modals
    volunteerCloseModal.addEventListener('click', () => {
        volunteerModal.style.display = 'none';
    });

    participantCloseModal.addEventListener('click', () => {
        participantModal.style.display = 'none';

    });

     // Preview the selected image in the Create Event modal
     const volunteerImageInput = document.getElementById('volunteerImage');
     const volunteerImagePreview = document.getElementById('eventImagePreview');
     const imagePlaceholder = document.getElementById('imagePlaceholder');
     
     volunteerImageInput.addEventListener('change', function (event) {
         const file = event.target.files[0];
         if (file) {
             const reader = new FileReader();
             reader.onload = function (e) {
                 volunteerImagePreview.src = e.target.result;
                 volunteerImagePreview.style.display = 'block'; // Show the image
                 imagePlaceholder.style.display = 'none'; // Hide the placeholder
             };
             reader.readAsDataURL(file);
         } else {
             eventImagePreview.src = '';
             eventImagePreview.style.display = 'none'; // Hide the image
             imagePlaceholder.style.display = 'flex'; // Show the placeholder
         }
     });
     
     const eventImageInput = document.getElementById('eventImageInput');
    const eventImagePreview = document.getElementById('eventImagePreviewEvent');
    const imagePlaceholderEvent = document.getElementById('imagePlaceholderEvent');

    eventImageInput.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                eventImagePreview.src = e.target.result;
                eventImagePreview.style.display = 'block'; // Show the image
                imagePlaceholderEvent.style.display = 'none'; // Hide the placeholder
            };
            reader.readAsDataURL(file);
        } else {
            eventImagePreview.src = '';
            eventImagePreview.style.display = 'none'; // Hide the image
            imagePlaceholderEvent.style.display = 'flex'; // Show the placeholder
        }
    });


});