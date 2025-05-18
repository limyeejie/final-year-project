// Global variables for pagination and search in each section
let currentPageUser = 1;
let currentPageEvent = 1;
let currentPageVolunteer = 1;
let currentPageContact = 1;
let resultsPerPageUser = 5;
let resultsPerPageEvent = 5;
let resultsPerPageVolunteer = 5;
let resultsPerPageContact = 5;
let userSearchQuery = '';
let eventSearchQuery = '';
let volunteerSearchQuery = '';
let contactSearchQuery = '';
let currentEditUserId = null; // Variable to track the user being edited
let currentEditEventId = null;
let currentEditVolunteer = null;
let currentEditContactId = null;


// Base URL for the API
const baseURL = '../templates/';


function fetchOverallPerformanceMetrics() {
    Promise.all([
        fetch(`${baseURL}fetch_users.php`).then(response => response.json()),
        fetch(`${baseURL}fetch_events.php`).then(response => response.json())
    ])
    .then(([userData, eventData]) => {
        const metrics = {
            totalOrganisers: userData.users ? userData.users.filter(user => user.role === 'Organizer').length : 0,
            totalParticipants: userData.users ? userData.users.filter(user => user.role === 'Student').length : 0,
            totalEvents: eventData.events ? eventData.events.length : 0
        };
        renderOverallPerformance(metrics);
    })
    .catch(error => console.error('Error fetching overall performance metrics:', error));
}

// Render the Overall Performance metrics section
function renderOverallPerformance(metrics) {
    document.getElementById('total-organisers').textContent = metrics.totalOrganisers;
    document.getElementById('total-participants').textContent = metrics.totalParticipants;
    document.getElementById('total-events').textContent = metrics.totalEvents;
}

// Fetch and render User data
function fetchUsers(page = 1, resultsPerPage = 5, search = '') {
    fetch(`${baseURL}fetch_users.php?page=${page}&results=${resultsPerPage}&search=${search}`)
        .then(response => response.json())
        .then(data => {
            console.log('Fetched Users:', data); // Debug the fetched data
            renderUserTable(data.users || [], page, data.totalPages || 1);
        })
        .catch(error => console.error('Error fetching users:', error));
}

// Render the User Management Table
function renderUserTable(users, page, totalPages) {
    const userTableBody = document.querySelector('.user-table tbody');
    userTableBody.innerHTML = '';

    if (users.length > 0) {
        users.forEach(user => {
            const row = `<tr>
                            <td>${user.id}</td>
                            <td>${user.full_name}</td>
                            <td>${user.email}</td>
                            <td>${user.role}</td>
                            <td><button class="action-btn" style="background-color:darkblue; border:none; color:white; padding:15px; border-radius:5px;" onclick="openEditModal('${user.id}', '${user.full_name}', '${user.email}', '${user.role}', '${user.contact_number}', '${user.date_of_birth}', '${user.gender}')">Edit</button></td>
                        </tr>`;
            userTableBody.innerHTML += row;
        });
    } else {
        userTableBody.innerHTML = `<tr><td colspan="5">No users found</td></tr>`;
    }

    document.querySelector('.page-counter-user').textContent = `Page ${page} of ${totalPages}`;
    document.querySelector('.prev-page-user').disabled = page === 1;
    document.querySelector('.next-page-user').disabled = page === totalPages;
}

// Open the Edit Modal and populate it with user data
function openEditModal(userId, userName, userEmail, userRole, contactNumber, dateOfBirth, gender) {
    currentEditUserId = userId;
    document.getElementById('editUserName').value = userName;
    document.getElementById('editUserEmail').value = userEmail;
    document.getElementById('editUserRole').value = userRole;
    document.getElementById('editUserContactNumber').value = contactNumber;
    document.getElementById('editUserDateOfBirth').value = dateOfBirth;
    document.getElementById('editUserGender').value = gender;
    
    console.log('Modal fields populated:', {
        id: userId,
        full_name: userName,
        email: userEmail,
        role: userRole,
        contact_number: contactNumber,
        date_of_birth: dateOfBirth,
        gender: gender
    });

    document.getElementById('editUserModal').style.display = 'flex';
}

// Close the Edit Modal
function closeEditModal() {
    document.getElementById('editUserModal').style.display = 'none';
    currentEditUserId = null;
}

// Handle Save Button Click in Edit Modal for User
document.getElementById('saveUserButton').addEventListener('click', function (e) {
    e.preventDefault();

    const updatedUser = {
        id: currentEditUserId,
        full_name: document.getElementById('editUserName').value.trim(),
        email: document.getElementById('editUserEmail').value.trim(),
        role: document.getElementById('editUserRole').value.trim(),
        contact_number: document.getElementById('editUserContactNumber').value.trim(),
        date_of_birth: document.getElementById('editUserDateOfBirth').value.trim(),
        gender: document.getElementById('editUserGender').value.trim()
    };

    if (!updatedUser.full_name || /[0-9]/.test(updatedUser.full_name)) {
        alert('Full Name is required and cannot contain numbers');
        return;
    } else if (!/^[a-zA-Z\s'-]+$/.test(updatedUser.full_name)) {
        alert('Full Name can only contain letters, spaces, hyphens, and apostrophes.');
        return;
    }

    if (!updatedUser.email || !/\S+@\S+\.\S+/.test(updatedUser.email)) {
        alert('Please enter a valid email address.');
        return;
    }

    if (!updatedUser.role) {
        alert('Please select a role.');
        return;
    }

    if (!updatedUser.contact_number) {
        alert('Contact Number is required.');
        return;
    } else if (!/^\+60\d{9,10}$/.test(updatedUser.contact_number)) {
        alert('Contact Number must start with +60 and be followed by 9 or 10 digits.');
        return;
    }

    if (!updatedUser.date_of_birth) {
        alert('Date of Birth is required.');
        return;
    } else {
        const dob = new Date(updatedUser.date_of_birth);
        const currentDate = new Date();
        
        if (dob > currentDate) {
            alert('Date of Birth cannot be in the future.');
            return;
        } 
        
        const age = currentDate.getFullYear() - dob.getFullYear();
        const monthDiff = currentDate.getMonth() - dob.getMonth();
        const dayDiff = currentDate.getDate() - dob.getDate();
    
        // Adjust age if birthday hasn't occurred this year
        const finalAge = monthDiff < 0 || (monthDiff === 0 && dayDiff < 0) 
            ? age - 1 
            : age;
    
        if (finalAge < 18) {
            alert('You must be at least 18 years old to register.');
            return;
        }
    }

    if (!updatedUser.gender) {
        alert('Please select a gender.');
        return;
    }

    // Submit the data
    fetch('update_user.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(updatedUser)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const redirectUrl = 'admin_dashboard.php';
            showSuccessModal(redirectUrl, 'User updated successfully');
            closeEditModal();
            fetchUsers(currentPageUser, resultsPerPageUser, userSearchQuery);
        } else {
            alert('Failed to update user: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => console.error('Error updating user:', error));
});



// Fetch and render Event data
function fetchEvents(page = 1, resultsPerPage = 5, search = '') {
    fetch(`${baseURL}fetch_events.php?page=${page}&results=${resultsPerPage}&search=${search}`)
        .then(response => response.json())
        .then(data => {
            console.log('Fetched Events:', data); 
            renderEventTable(data.events || [], page, data.totalPages || 1);
        })
        .catch(error => console.error('Error fetching events:', error));
}

// Render the Event Management Table
function renderEventTable(events, page, totalPages) {
    const eventTableBody = document.querySelector('.event-table tbody');
    eventTableBody.innerHTML = '';

    if (events.length > 0) {
        events.forEach(event => {
            const row = `<tr>
                            <td>${event.id}</td>
                            <td>${event.title}</td>
                            <td>${event.organizer_contact}</td>
                            <td>${event.date}</td>
                            <td>
                                <button class="action-btn" style="background-color:darkblue; border:none; color:white; padding:15px; border-radius:5px;" onclick="openEditEventModal(
                                    '${event.id}', 
                                    '${event.title}', 
                                    '${event.organizer_contact}', 
                                    '${event.date}', 
                                    '${event.start_registration_date}', 
                                    '${event.due_registration_date}', 
                                    '${event.location}', 
                                    '${event.description}', 
                                    '${event.event_category}', 
                                    '${event.max_participants}',
                                    '${event.organizer_name}',
                                    '${event.organizer_number}',
                                    '${event.event_time}'
                                )">Edit</button>
                            </td>
                        </tr>`;
            eventTableBody.innerHTML += row;
        });
    } else {
        eventTableBody.innerHTML = `<tr><td colspan="5">No events found</td></tr>`;
    }

    document.querySelector('.page-counter-event').textContent = `Page ${page} of ${totalPages}`;
    document.querySelector('.prev-page-event').disabled = page === 1;
    document.querySelector('.next-page-event').disabled = page === totalPages;
}

// Open the Edit Modal and populate it with event data
function openEditEventModal(eventId, title, organizerContact, date, startRegDate, 
    dueRegDate, location, description, eventCategory, maxParticipants, organizerName, organizerNumber, eventTime) {
    currentEditEventId = eventId;
    document.getElementById('editEventName').value = title;
    document.getElementById('editEventOrganizerEmail').value = organizerContact;
    document.getElementById('editEventDate').value = date;
    document.getElementById('editEventStartRegDate').value = startRegDate;
    document.getElementById('editEventDueRegDate').value = dueRegDate;
    document.getElementById('editEventLocation').value = location;
    document.getElementById('editEventDescription').value = description;
    document.getElementById('editEventCategory').value = eventCategory;
    document.getElementById('editEventMaxParticipants').value = maxParticipants;
    document.getElementById('editEventOrganizerNumber').value = organizerNumber;
    document.getElementById('editEventTime').value = eventTime;
    document.getElementById('editEventOrganizerName').value = organizerName;

    console.log('Modal fields populated:', {
        id: eventId,
        title,
        organizerContact,
        date,
        startRegDate,
        dueRegDate,
        location,
        description,
        eventCategory,
        maxParticipants,
        organizerName,
        organizerNumber,
        eventTime
    });

    document.getElementById('editEventModal').style.display = 'flex';
}

// Close the Edit Modal
function closeEditEventModal() {
    document.getElementById('editEventModal').style.display = 'none';
    currentEditEventId = null;
}

document.getElementById('saveEventButton').addEventListener('click', function (e) {
    e.preventDefault();

    const updatedEvent = {
        id: currentEditEventId,
        title: document.getElementById('editEventName').value.trim(),
        organizer_contact: document.getElementById('editEventOrganizerEmail').value.trim(),
        date: document.getElementById('editEventDate').value.trim(),
        start_registration_date: document.getElementById('editEventStartRegDate').value.trim(),
        due_registration_date: document.getElementById('editEventDueRegDate').value.trim(),
        location: document.getElementById('editEventLocation').value.trim(),
        description: document.getElementById('editEventDescription').value.trim(),
        event_category: document.getElementById('editEventCategory').value.trim(),
        max_participants: document.getElementById('editEventMaxParticipants').value.trim(),
        organizer_name: document.getElementById('editEventOrganizerName').value.trim(),
        organizer_number: document.getElementById('editEventOrganizerNumber').value.trim(),
        event_time: document.getElementById('editEventTime').value.trim()
    };

    // Get today's date in the format YYYY-MM-DD
    const today = new Date().toISOString().split('T')[0];

    // Validations
    if (!updatedEvent.title) {
        alert('Please enter an event title.');
        return;
    }

    if (!updatedEvent.organizer_contact || !/\S+@\S+\.\S+/.test(updatedEvent.organizer_contact)) {
        alert('Please enter a valid organizer contact (email).');
        return;
    }

    if (!updatedEvent.date || new Date(updatedEvent.date) <= new Date(today)) {
        alert('Please enter a valid event date (must be in the future).');
        return;
    }

    if (!updatedEvent.start_registration_date || new Date(updatedEvent.start_registration_date) < new Date(today)) {
        alert('Start registration date must be today or in the future.');
        return;
    }

    if (!updatedEvent.due_registration_date || new Date(updatedEvent.due_registration_date) < new Date(today)) {
        alert('Due registration date must be today or in the future.');
        return;
    }

    if (!updatedEvent.start_registration_date || new Date(updatedEvent.start_registration_date) >= new Date(updatedEvent.date)) {
        alert('Start registration date must be before the event date.');
        return;
    }

    if (!updatedEvent.due_registration_date || new Date(updatedEvent.due_registration_date) >= new Date(updatedEvent.date)) {
        alert('Due registration date must be before the event date.');
        return;
    }

    if (!updatedEvent.location) {
        alert('Please enter the event location.');
        return;
    }

    if (!updatedEvent.event_category) {
        alert('Please select a valid event category.');
        return;
    }

    if (!updatedEvent.max_participants || isNaN(updatedEvent.max_participants) || updatedEvent.max_participants <= 0) {
        alert('Please enter a valid number for max participants.');
        return;
    }

    if (!updatedEvent.event_time) {
        alert('Event time is required.');
        return;
    }
    if (!/^(?:2[0-3]|[01]?[0-9]):[0-5][0-9]$/.test(updatedEvent.event_time)) {
        alert('Event time must be in the format HH:MM (24-hour format).');
        return;
    }

    // New Validation for Organizer Phone (Malaysian phone number format)
    if (!updatedEvent.organizer_number) {
        alert('Organizer phone number is required.');
        return;
    }
    if (!/^\+60\d{9,10}$/.test(updatedEvent.organizer_number)) {
        alert('Organizer phone number must start with +60 and be followed by 9 or 10 digits.');
        return;
    }

    // New Validation for Organizer Name (letters, spaces, hyphens, apostrophes)
    if (!updatedEvent.organizer_name) {
        alert('Organizer name is required.');
        return;
    }
    if (!/^[a-zA-Z\s'-]+$/.test(updatedEvent.organizer_name)) {
        alert('Organizer name can only contain letters, spaces, hyphens, and apostrophes.');
        return;
    }

    if (!updatedEvent.description) {
        alert('Please enter the event description.');
        return;
    }

    // Submit the data
    fetch('update_events.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(updatedEvent)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const redirectUrl = 'admin_dashboard.php';
            showSuccessModal(redirectUrl, 'Event updated successfully');
            closeEditEventModal();
            fetchEvents(); // Reload the events list
        } else {
            alert('Failed to update event: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => console.error('Error updating event:', error));
});




// Fetch and render Volunteer data
function fetchVolunteers(page = 1, resultsPerPage = 5, search = '') {
    fetch(`${baseURL}fetch_volunteers.php?page=${page}&results=${resultsPerPage}&search=${search}`)
        .then(response => response.json())
        .then(data => {
            console.log('Fetched Volunteers:', data); // Debug fetched data
            renderVolunteerTable(data.volunteers || [], page, data.totalPages || 1);
        })
        .catch(error => console.error('Error fetching volunteers:', error));
}

// Render the Volunteer Management Table
function renderVolunteerTable(volunteers, page, totalPages) {
    const volunteerTableBody = document.querySelector('.volunteer-table tbody');
    volunteerTableBody.innerHTML = '';

    if (volunteers.length > 0) {
        volunteers.forEach(volunteer => {
            const row = `<tr>
                            <td>${volunteer.id}</td>
                            <td>${volunteer.title}</td>
                            <td>${volunteer.date}</td>
                            <td>${volunteer.location}</td>
                            <td>${volunteer.description}</td>
                            <td>${volunteer.requirement}</td>
                            <td>${volunteer.max_volunteer}</td>
                            <td>${volunteer.organizer_contact}</td>
                            <td>
                                <button class="action-btn" style="background-color:darkblue; border:none; color:white; padding:15px; border-radius:5px;" onclick="openEditVolunteerModal(
                                    '${volunteer.id}', 
                                    '${volunteer.title}', 
                                    '${volunteer.date}', 
                                    '${volunteer.location}', 
                                    '${volunteer.description}', 
                                    '${volunteer.requirement}', 
                                    '${volunteer.max_volunteer}', 
                                    '${volunteer.organizer_contact}',
                                    '${volunteer.organizer_name}',
                                    '${volunteer.organizer_number}',
                                    '${volunteer.event_time}',
                                )">Edit</button>
                            </td>
                        </tr>`;
            volunteerTableBody.innerHTML += row;
        });
    } else {
        volunteerTableBody.innerHTML = `<tr><td colspan="9">No volunteers found</td></tr>`;
    }

    document.querySelector('.page-counter-volunteer').textContent = `Page ${page} of ${totalPages}`;
    document.querySelector('.prev-page-volunteer').disabled = page === 1;
    document.querySelector('.next-page-volunteer').disabled = page === totalPages;
}

// Open the Edit Modal and populate it with volunteer data
function openEditVolunteerModal(volunteerId, title, date, location, description, requirement, maxVolunteers, organizerContact, organizerName, organizerNumber, eventTime) {
    currentEditVolunteerId = volunteerId;
    document.getElementById('editVolunteerTitle').value = title;
    document.getElementById('editVolunteerDate').value = date;
    document.getElementById('editVolunteerLocation').value = location;
    document.getElementById('editVolunteerDescription').value = description;
    document.getElementById('editVolunteerRequirement').value = requirement;
    document.getElementById('editVolunteerMaxVolunteers').value = maxVolunteers;
    document.getElementById('editVolunteerContact').value = organizerContact;
    document.getElementById('editVolunteerTime').value = eventTime;
    document.getElementById('editOrganizerName').value = organizerName;
    document.getElementById('editOrganizerNumber').value = organizerNumber;

    console.log('Modal fields populated:', {
        id: volunteerId,
        title,
        date,
        location,
        description,
        requirement,
        maxVolunteers,
        organizerContact,
        eventTime,
        organizerName,
        organizerNumber
    });

    document.getElementById('editVolunteerModal').style.display = 'flex';
}

// Close the Edit Modal
function closeEditVolunteerModal() {
    document.getElementById('editVolunteerModal').style.display = 'none';
    currentEditVolunteerId = null;
}

// Handle Save Button Click in Edit Modal for Volunteer
document.getElementById('saveVolunteerButton').addEventListener('click', function (e) {
    e.preventDefault();

    const updatedVolunteer = {
        id: currentEditVolunteerId,
        title: document.getElementById('editVolunteerTitle').value.trim(),
        date: document.getElementById('editVolunteerDate').value.trim(),
        location: document.getElementById('editVolunteerLocation').value.trim(),
        description: document.getElementById('editVolunteerDescription').value.trim(),
        requirement: document.getElementById('editVolunteerRequirement').value.trim(),
        max_volunteer: document.getElementById('editVolunteerMaxVolunteers').value.trim(),
        organizer_contact: document.getElementById('editVolunteerContact').value.trim(),
        organizer_name: document.getElementById('editOrganizerName').value.trim(),
        organizer_number: document.getElementById('editOrganizerNumber').value.trim(),
        event_time: document.getElementById('editVolunteerTime').value.trim()
    };

    // Validations
    if (!updatedVolunteer.title) {
        alert('Please enter the volunteer title.');
        return;
    }

    if (!updatedVolunteer.date || new Date(updatedVolunteer.date) <= new Date()) {
        alert('Please enter a valid volunteer event date (must be in the future).');
        return;
    }

    if (!updatedVolunteer.location) {
        alert('Please enter the location.');
        return;
    }

    if (!updatedVolunteer.description) {
        alert('Please enter the description.');
        return;
    }

    if (!updatedVolunteer.requirement) {
        alert('Please specify the volunteer requirements.');
        return;
    }

    if (!updatedVolunteer.max_volunteer || isNaN(updatedVolunteer.max_volunteer) || updatedVolunteer.max_volunteer <= 0) {
        alert('Please enter a valid number for max volunteers.');
        return;
    }

    if (!updatedVolunteer.organizer_contact || 
        (updatedVolunteer.organizer_contact.length < 10 && !/\S+@\S+\.\S+/.test(updatedVolunteer.organizer_contact)) || 
        (!/^\d+$/.test(updatedVolunteer.organizer_contact) && !/\S+@\S+\.\S+/.test(updatedVolunteer.organizer_contact))) {
        alert('Please enter a valid organizer contact number or email.');
        return;
    }

    // Organizer Name Validation
if (!updatedVolunteer.organizer_name) {
    alert('Organizer name is required.');
    return;
} else if (!/^[a-zA-Z\s'-]+$/.test(updatedVolunteer.organizer_name)) {
    alert('Organizer name can only contain letters, spaces, hyphens, and apostrophes.');
    return;
}

// Event Time Validation
if (!updatedVolunteer.event_time) {
    alert('Event time is required.');
    return;
} else if (!/^(?:2[0-3]|[01]?[0-9]):[0-5][0-9]$/.test(updatedVolunteer.event_time)) {
    alert('Event time must be in the format HH:MM (24-hour format).');
    return;
}

// Organizer Phone Validation
if (!updatedVolunteer.organizer_number) {
    alert('Organizer phone number is required.');
    return;
} else if (!/^\+60\d{9,10}$/.test(updatedVolunteer.organizer_number) ){
    alert('Organizer phone number must start with +60 and be followed by 9 or 10 digits.');
    return;
}
    
    // Submit the data
    fetch('update_volunteers.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(updatedVolunteer)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const redirectUrl = 'admin_dashboard.php';
            showSuccessModal(redirectUrl, 'Volunteer event updated successfully');
            closeEditVolunteerModal();
            fetchVolunteers(); // Reload the volunteers list
        } else {
            alert('Failed to update volunteer: ' + (data.error || 'Unknown error'));
            console.error('Update Error Details:', data);
        }
    })
    .catch(error => console.error('Error updating volunteer:', error));
});




// Fetch and render Contact Us data
function fetchContactUsData(page = 1, resultsPerPage = 5, search = '') {
    fetch(`${baseURL}fetch_contact_us.php?page=${page}&results=${resultsPerPage}&search=${search}`)
        .then(response => response.json())
        .then(data => {
            console.log('Fetched Contact Us:', data); // Debug fetched data
            renderContactUsTable(data.contacts || [], page, data.totalPages || 1);
        })
        .catch(error => console.error('Error fetching Contact Us data:', error));
}



// Render the Contact Us Management Table
function renderContactUsTable(contacts, page, totalPages) {
    const contactTableBody = document.querySelector('.contact-us-table tbody');
    contactTableBody.innerHTML = '';

    if (contacts.length > 0) {
        contacts.forEach(contact => {
            const row = `<tr>
                            <td>${contact.id}</td>
                            <td>${contact.fullname}</td>
                            <td>${contact.email}</td>
                            <td>${contact.subject}</td>
                            <td>${contact.reason}</td>
                            <td>${contact.role}</td>
                            <td>${contact.message}</td>
                            <td>
                                <button class="action-btn" style="background-color:darkblue; border:none; color:white; padding:15px; border-radius:5px;" onclick="openEditContactModal(
                                    '${contact.id}', 
                                    '${contact.fullname}', 
                                    '${contact.email}', 
                                    '${contact.subject}', 
                                    '${contact.reason}', 
                                    '${contact.role}', 
                                    '${contact.message}'
                                )">Edit</button>
                            </td>
                        </tr>`;
            contactTableBody.innerHTML += row;
        });
    } else {
        contactTableBody.innerHTML = `<tr><td colspan="8">No contact messages found</td></tr>`;
    }

    document.querySelector('.page-counter-contact').textContent = `Page ${page} of ${totalPages}`;
    document.querySelector('.prev-page-contact').disabled = page === 1;
    document.querySelector('.next-page-contact').disabled = page === totalPages;
}

// Open the Edit Modal and populate it with contact data
function openEditContactModal(contactId, fullname, email, subject, reason, role, message) {
    currentEditContactId = contactId;

    const fullnameField = document.getElementById('editContactFullname');
    const emailField = document.getElementById('editContactEmail');
    const subjectField = document.getElementById('editContactSubject');
    const reasonField = document.getElementById('editContactReason');
    const roleField = document.getElementById('editContactRole');
    const messageField = document.getElementById('editContactMessage');

    if (fullnameField) fullnameField.value = fullname;
    if (emailField) emailField.value = email;
    if (subjectField) subjectField.value = subject;
    if (reasonField) reasonField.value = reason;
    if (roleField) roleField.value = role;
    if (messageField) messageField.value = message;

    console.log('Modal fields populated:', {
        id: contactId,
        fullname,
        email,
        subject,
        reason,
        role,
        message,
    });

    const modal = document.getElementById('editContactModal');
    if (modal) modal.style.display = 'flex';
}


// Close the Edit Modal
function closeEditContactModal() {
    document.getElementById('editContactModal').style.display = 'none';
    currentEditContactId = null;
}

// Handle Save Button Click in Edit Modal for Contact Us
document.getElementById('saveContactButton').addEventListener('click', function (e) {
    e.preventDefault();

    const errors = {};

    const updatedContact = {
        id: currentEditContactId,
        fullname: document.getElementById('editContactFullname').value.trim(),
        email: document.getElementById('editContactEmail').value.trim(),
        subject: document.getElementById('editContactSubject').value.trim(),
        reason: document.getElementById('editContactReason').value.trim(),
        role: document.getElementById('editContactRole').value.trim(),
        message: document.getElementById('editContactMessage').value.trim()
    };

    //input validations
    if (!updatedContact.fullname || /[0-9]/.test(updatedContact.fullname)) {
        alert('Full Name is required and cannot contain numbers');
        return;
    } else if (!/^[a-zA-Z\s'-]+$/.test(updatedContact.fullname)) {
        alert('Full Name can only contain letters, spaces, hyphens, and apostrophes.');
        return;
    }


    if (!updatedContact.email || !/\S+@\S+\.\S+/.test(updatedContact.email)) {
        alert('Please enter a valid email address.');
        return;
    }

 
    if (!updatedContact.subject) {
        alert('Please enter the subject of your message.');
        return;
    }


    if (!updatedContact.reason) {
        alert('Please provide a valid reason.');
        return;
    }

   
    if (!updatedContact.role) {
        alert('Please provide a valid role.');
        return;
    }

   
    if (!updatedContact.message) {
        alert('Please enter your message.');
        return;
    }


    fetch('update_contact_us.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(updatedContact)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const redirectUrl = 'admin_dashboard.php';
            showSuccessModal(redirectUrl, 'Contact updated successfully');
            closeEditContactModal();
            fetchContactUsData(); 
        } else {
            alert('Failed to update contact: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => console.error('Error updating contact:', error));
});

// Delete User
function deleteUser() {
    if (confirm("Are you sure you want to delete this user?")) {
        fetch(`${baseURL}delete_users.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: currentEditUserId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const redirectUrl = 'admin_dashboard.php';
                showSuccessModal(redirectUrl, 'User deleted successfully');
                closeEditModal();
                fetchUsers(currentPageUser, resultsPerPageUser, userSearchQuery); // Reload user list
            } else {
                alert('Failed to delete user');
            }
        })
        .catch(error => console.error('Error deleting user:', error));
    }
}

// Delete Event
function deleteEvent() {
    if (confirm("Are you sure you want to delete this event?")) {
        fetch(`${baseURL}delete_event.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: currentEditEventId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const redirectUrl = 'admin_dashboard.php';
                showSuccessModal(redirectUrl, 'Event deleted successfully');
                closeEditEventModal();
                fetchEvents(currentPageEvent, resultsPerPageEvent, eventSearchQuery); // Reload event list
            } else {
                alert('Failed to delete event');
            }
        })
        .catch(error => console.error('Error deleting event:', error));
    }
}

// Delete Volunteer
function deleteVolunteer() {
    if (confirm("Are you sure you want to delete this volunteer opportunity?")) {
        fetch(`${baseURL}delete_volunteer.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: currentEditVolunteerId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const redirectUrl = 'admin_dashboard.php';
                showSuccessModal(redirectUrl, 'Volunteer event deleted successfully');
                closeEditVolunteerModal();
                fetchVolunteers(currentPageVolunteer, resultsPerPageVolunteer, volunteerSearchQuery); // Reload volunteer list
            } else {
                alert('Failed to delete volunteer opportunity');
            }
        })
        .catch(error => console.error('Error deleting volunteer:', error));
    }
}

// Delete Contact
function deleteContact() {
    if (confirm("Are you sure you want to delete this contact inquiry?")) {
        fetch(`${baseURL}delete_contact.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: currentEditContactId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const redirectUrl = 'admin_dashboard.php';
                showSuccessModal(redirectUrl, 'Contact Us message deleted successfully');
                closeEditContactModal();
                fetchContactUsData(currentPageContact, resultsPerPageContact, contactSearchQuery); // Reload contact list
            } else {
                alert('Failed to delete contact inquiry');
            }
        })
        .catch(error => console.error('Error deleting contact inquiry:', error));
    }
}


// Event listeners for search inputs and pagination in each section
document.querySelector('.search-input-user').addEventListener('input', function() {
    userSearchQuery = this.value;
    currentPageUser = 1;
    fetchUsers(currentPageUser, resultsPerPageUser, userSearchQuery);
});

document.querySelector('.search-input-event').addEventListener('input', function() {
    eventSearchQuery = this.value;
    currentPageEvent = 1;
    fetchEvents(currentPageEvent, resultsPerPageEvent, eventSearchQuery);
});

document.querySelector('.search-input-volunteer').addEventListener('input', function() {
    volunteerSearchQuery = this.value;
    currentPageVolunteer = 1;
    fetchVolunteers(currentPageVolunteer, resultsPerPageVolunteer, volunteerSearchQuery);
});

document.querySelector('.search-input-contact').addEventListener('input', function() {
    contactSearchQuery = this.value;
    currentPageContact = 1;
    fetchContactUsData(currentPageContact, resultsPerPageContact, contactSearchQuery);
});

// Handle Results Per Page Change for each section
document.getElementById('results-per-page-user').addEventListener('change', function() {
    resultsPerPageUser = parseInt(this.value);
    currentPageUser = 1;
    fetchUsers(currentPageUser, resultsPerPageUser, userSearchQuery);
});

document.getElementById('results-per-page-event').addEventListener('change', function() {
    resultsPerPageEvent = parseInt(this.value);
    currentPageEvent = 1;
    fetchEvents(currentPageEvent, resultsPerPageEvent, eventSearchQuery);
});

document.getElementById('results-per-page-volunteer').addEventListener('change', function() {
    resultsPerPageVolunteer = parseInt(this.value);
    currentPageVolunteer = 1;
    fetchVolunteers(currentPageVolunteer, resultsPerPageVolunteer, volunteerSearchQuery);
});

document.getElementById('results-per-page-contact').addEventListener('change', function() {
    resultsPerPageContact = parseInt(this.value);
    currentPageContact = 1;
    fetchContactUsData(currentPageContact, resultsPerPageContact, contactSearchQuery);
});

// Pagination Event Listeners for Users, Events, Volunteers, and Contact Us
document.querySelector('.prev-page-user').addEventListener('click', () => {
    if (currentPageUser > 1) {
        currentPageUser--;
        fetchUsers(currentPageUser, resultsPerPageUser, userSearchQuery);
    }
});

document.querySelector('.next-page-user').addEventListener('click', () => {
    fetchUsers(++currentPageUser, resultsPerPageUser, userSearchQuery);
});

document.querySelector('.prev-page-event').addEventListener('click', () => {
    if (currentPageEvent > 1) {
        currentPageEvent--;
        fetchEvents(currentPageEvent, resultsPerPageEvent, eventSearchQuery);
    }
});

document.querySelector('.next-page-event').addEventListener('click', () => {
    fetchEvents(++currentPageEvent, resultsPerPageEvent, eventSearchQuery);
});

document.querySelector('.prev-page-volunteer').addEventListener('click', () => {
    if (currentPageVolunteer > 1) {
        currentPageVolunteer--;
        fetchVolunteers(currentPageVolunteer, resultsPerPageVolunteer, volunteerSearchQuery);
    }
});

document.querySelector('.next-page-volunteer').addEventListener('click', () => {
    fetchVolunteers(++currentPageVolunteer, resultsPerPageVolunteer, volunteerSearchQuery);
});

document.querySelector('.prev-page-contact').addEventListener('click', () => {
    if (currentPageContact > 1) {
        currentPageContact--;
        fetchContactUsData(currentPageContact, resultsPerPageContact, contactSearchQuery);
    }
});

document.querySelector('.next-page-contact').addEventListener('click', () => {
    fetchContactUsData(++currentPageContact, resultsPerPageContact, contactSearchQuery);
});

// Initial render on page load for all sections
document.addEventListener('DOMContentLoaded', () => {
    fetchOverallPerformanceMetrics();
    fetchUsers(currentPageUser, resultsPerPageUser, userSearchQuery);
    fetchEvents(currentPageEvent, resultsPerPageEvent, eventSearchQuery);
    fetchVolunteers(currentPageVolunteer, resultsPerPageVolunteer, volunteerSearchQuery);
    fetchContactUsData(currentPageContact, resultsPerPageContact, contactSearchQuery);
});
