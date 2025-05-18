<?php
include '../config/connection.php';
include 'session_check.php';

check_user_logged_in();
check_if_student();

// Initialize variables for event details
$eventDetails = null;
$eventDetail = null;

if (isset($_GET['viewEventId'])) {
    $eventId = intval($_GET['viewEventId']); // Get the event ID from the URL

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $eventId); // Bind the event ID

    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the event details if available
    if ($result->num_rows > 0) {
        $eventDetails = $result->fetch_assoc();
    } else {
        // Handle case where event is not found
        $eventDetails = null; // You can set an error message if needed
    }

    // Close the statement
    $stmt->close();
} elseif (isset($_GET['editEventId'])) {
    $eventId = intval($_GET['editEventId']); // Get the event ID from the URL

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $eventId); // Bind the event ID

    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the event details if available
    if ($result->num_rows > 0) {
        $eventDetail = $result->fetch_assoc();
    } else {
        // Handle case where event is not found
        $eventDetail = null; // You can set an error message if needed
    }

    // Close the statement
    $stmt->close();
} elseif (isset($_GET['editVolunteerId'])) {
    $volunteerId = intval($_GET['editVolunteerId']); // Get the event ID from the URL

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM Volunteers WHERE id = ?");
    $stmt->bind_param("i", $volunteerId); // Bind the event ID

    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the event details if available
    if ($result->num_rows > 0) {
        $volunteerDetail = $result->fetch_assoc();
    } else {
        // Handle case where event is not found
        $volunteerDetail = null; // You can set an error message if needed
    }

    // Close the statement
    $stmt->close();
} elseif (isset($_GET['viewVolunteerId'])) {
    $volunteerId = intval($_GET['viewVolunteerId']); // Get the event ID from the URL

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM Volunteers WHERE id = ?");
    $stmt->bind_param("i", $volunteerId); // Bind the event ID

    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the event details if available
    if ($result->num_rows > 0) {
        $volunteerDetails = $result->fetch_assoc();
    } else {
        // Handle case where event is not found
        $volunteerDetails = null; // You can set an error message if needed
    }

    // Close the statement
    $stmt->close();
}


// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organiser Event Management</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/management.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/purecss@2.0.6/build/pure-min.css">
    <script src="../js/manage_script.js"></script>
    <?php include('modal.php'); ?>
    <script src="../js/modal.js"></script>

</head>
<body>
    <!-- Header Section -->
    <?php include('header.php'); ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-text-box">
            <h1>Manage Your Events</h1>
            <p>Effortlessly organize, edit, and track participants and volunteers for your events.</p>
        </div>
    </section>

    <!-- Tabs for Volunteers & Participants -->
    <div class="tabs">
        <button class="tab-button active" id="volunteers-tab"> 
            <i class="material-icons"></i> Volunteers
        </button>
        <button class="tab-button" id="participants-tab">
            <i class="material-icons"></i> Participants
        </button>
    </div>

    <!-- Search Bar Section -->
    <div class="search-bar">
        <div class="search-input-wrapper">
            <label for="search-input" class="search-label">Search by Keywords</label>
            <div class="input-group">
                <input type="text" id="search-input" placeholder="Hinted search text">
                <button class="filter-button" onclick="loadEvents()"><i class="fas fa-search"></i></button>
            </div>
            <div class="sort-by">
            <label for="sort" class="sort-label">Sort By</label>
                <select name="sort" id="sort" onchange="sortBoth()">
                    <option value="title">Name</option>
                    <option value="date">Date</option>
                    <option value="location">Location</option>
                </select>
            </div>
        </div>

        <!-- Dropdown for number of items per page -->
        <div class="results-and-pagination">
        <label for="results-per-page" class="results-per-page-label">Results per Page</label>
            <div class="results-per-page">
                <select name="results" id="results" onchange="updateResultsPerPage()">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
            </div>

            <!-- Pagination Control -->
            <div class="pagination">
                <button class="pagination-button" onclick="prevPage()" id="prev-btn" disabled>
                    <i class="fas fa-chevron-left"></i> Previous
                </button>
                <span id="current-page">Page 1 of 10</span>
                <button class="pagination-button" onclick="nextPage()" id="next-btn">
                    Next <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Event List Section for Volunteers -->
    <div class="event-section volunteers-section">
        <button class="create-event-btn" id="createVolunteerEventBtn">+ Create New Volunteer Event</button>
        <br><br>
        <!-- Volunteer Event List (Cards or Table Layout) -->
        
    <table class="event-table">
        <thead>
            <tr>
                <th>Event Image</th>
                <th>Event Name</th>
                <th>Date</th>
                <th>Location</th>
                <th>Max Volunteer</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
    </div>

    <!-- Event List Section for Participants -->
    <div class="event-section participants-section" style="display: none;">
        <button class="create-event-btn" id="createParticipantEventBtn">+ Create New Participant Event</button>
        <br><br>
        <!-- Participant Event List (Cards or Table Layout) -->
    </button>
    <table class="event-table">
        <thead>
            <tr>
                <th>Event Image</th>
                <th>Event Name</th>
                <th>Date</th>
                <th>Location</th>
                <th>Max Participants</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
    </div>

    <!-- Updated Create Volunteer Modal -->
<div class="modal" id="volunteerModal">
    <div class="modal-content">
        <div class="modal-left">
            <span class="close-btn" id="volunteerCloseModal">&times;</span>
            <h2>Create New Event</h2>
            <br>
            <form id="createEventForm" method="POST" action="create_volunteer.php" enctype="multipart/form-data">
                <label for="volunteerName">Event Name:</label>
                <input type="text" id="volunteerName" placeholder="Enter the event name"name="volunteerName" required>
                <br><br>

                <label for="organizerName">Organizer Name:</label>
                <input type="text" id="organizerName" placeholder="Enter the organizer's name"name="organizerName" required>
                <br><br>

                <label for="organizerContact">Organizer Contact (Email):</label>
                <input type="text" id="organizerContact" placeholder="Enter the organizer's email address"name="organizerContact" required>
                <br><br>

                <label for="organizerPhone">Organizer Contact (Phone):</label>
                <input type="text" id="organizerPhone" placeholder="Enter the organizer's phone number" name="organizerPhone" required>
                <br><br>

                <label for="volunteerDate">Event Date:</label>
                <input type="date" id="volunteerDate" placeholder="Select the event date" name="volunteerDate" required>
                <br><br>

                <label for="volunteerTime">Event Time:</label>
                <input type="time" id="volunteerTime" placeholder="Select the event time" name="volunteerTime" required>
                <br><br>
                
                <label for="volunteerLocation">Event Location:</label>
                <input type="text" id="volunteerLocation" placeholder="Enter the event location" name="volunteerLocation" required>
                <br><br>
                <label for="volunteerDescription">Volunteer Description:</label>
                <textarea id="volunteerDescription" placeholder="Provide a brief description of the event" name="volunteerDescription" required></textarea>
                <br><br>

                <label for="volunteerRequirement">Volunteer Requirements:</label>
                <input type="text" id="volunteerRequirement" placeholder="List volunteer requirement" name="volunteerRequirement" required>
                <br><br>

                <label for="volunteerImage" >Event Image:</label>
                <input type="file" id="volunteerImage" placeholder="Upload an image for the event" name="volunteerImage" accept="image/*" >
                <br><br>
                <label for="maxVolunteer" id="maxVolunteer" >Max Volunteers:</label>
                <input type="number" id="maxVolunteer" placeholder="max number volunteers" name="maxVolunteer">
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px;">
                    <button type="submit" class="submit-btn">Create Event</button>
                    <button type="reset" class="submit-btn" style="background-color: red;">Clear Form</button>
                </div>
                <br>
            </form>
        </div>
        <div class="modal-right">
            <div id="imagePlaceholder" class="placeholder">
                <p>Upload to view here</p>
            </div>
            <img id="eventImagePreview" src="" alt="Event Image Preview" style="display: none; max-width: 300px; height: 400px"> <!-- Initially hidden -->
        </div>
    </div>
</div>


<!---Create Event Modal --->
<div class="modal" id="participantModal">
    <div class="modal-content">
        <div class="modal-left">
            <span class="close-btn" id="participantCloseModal">&times;</span>
            <h2>Create New Event</h2>
            <br>
            <form id="createEventForm" method="POST" action="create_event.php" enctype="multipart/form-data">
                <label for="eventName">Event Name:</label>
                <input type="text" id="eventName" placeholder="Enter event name" name="eventName" required>
                <br><br>
                <label for="organizerName">Organizer Name:</label>
                <input type="text" id="organizerName" placeholder="Enter organizer name" name="organizerName" required>
                <br><br>
                <label for="organizerContact">Organizer Contact (Email):</label>
                <input type="text" id="organizerContact" placeholder="Enter organizer email" name="organizerContact" required>
                <br><br>
                <label for="organizerPhone">Organizer Contact (Phone):</label>
                <input type="text" id="organizerPhone" placeholder="Enter organizer phone num" name="organizerPhone" required>
                <br><br>
                <label for="eventDate">Event Date:</label>
                <input type="date" id="eventDate" placeholder="Select event date" name="eventDate" required>
                <br><br>
                <label for="eventTime">Event Time:</label>
                <input type="time" id="eventTime" placeholder="Select event time" name="eventTime" required>
                <br><br>
                <label for="startRegDate">Start Registration Date:</label>
                <input type="date" id="startRegDate" placeholder="Select start registration date" name="startRegDate" required>
                <br><br>
                <label for="dueRegDate">Due Registration Date:</label>
                <input type="date" id="dueRegDate" placeholder="Select due registration date" name="dueRegDate" required>
                <br><br>
                <label for="eventLocation">Event Location:</label>
                <input type="text" id="eventLocation" placeholder="Enter event location" name="eventLocation" required>
                <br><br>
                <label for="eventDescription">Event Description:</label>
                <textarea id="eventDescription" placeholder="Provide a brief description of the event" name="eventDescription" required></textarea>
                <br><br>
                <label for="eventCategory">Event Category:</label>
                <select class="form-control" id="eventCategory" placeholder="Select event category" name="eventCategory" required>
                    <option value="" selected>Select One</option>
                    <option value="Community Clean-Up" selected>Community Clean-Up</option>
                    <option value="Skills Sharing Session">Skills Sharing Session</option>
                    <option value="Charity Event">Charity Event</option>
                </select>
                <br><br>
                <label for="eventImage">Event Image:</label>
                <input type="file" id="eventImageInput" placeholder="Upload event image" name="eventImage" accept="image/*">
                <br><br>
                
                <label for="maxParticipants" id="participantLabel" style="display: flex;">Max Participants:</label>
                <input type="number" id="maxParticipants" placeholder="Enter max participants" name="maxParticipants" style="display: flex;">

                <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px;">
                    <button type="submit" class="submit-btn">Create Event</button>
                    <button type="reset" class="submit-btn" style="background-color: red;">Clear Form</button>
                </div>
                <br>
            </form>
        </div>
        <div class="modal-right">
        <div id="imagePlaceholderEvent" class="placeholder">
                <p>Upload to view here</p>
            </div>
            <img id="eventImagePreviewEvent" src="" alt="Event Image Preview" style="display: none; max-width: 300px; height: 400px"> <!-- Initially hidden -->
        </div>
    </div>
</div>

<!-- Edit Volunteer Modal (same structure as Create Event Modal, but prefilled data) -->
<div class="modal" id="editVolunteerModal" style="display: <?php echo $volunteerDetail ? 'flex' : 'none'; ?>;">
    <div class="modal-content">
        <div class="modal-left">
            <span class="close-btn" id="closeEditVolunteerModal">&times;</span>
            <h2>Edit Volunteer Event</h2>
            <br>
            <form id="editVolunteerEventForm" method="POST" action="update_volunteer.php" enctype="multipart/form-data">
                <input type="hidden" name="editVolunteerId" value="<?php echo $volunteerDetail['id']; ?>">
                <label for="editVolunteerName">Event Name:</label>
                <input type="text" id="editVolunteerName" name="editVolunteerName" value="<?php echo htmlspecialchars($volunteerDetail['title']); ?>" required>
                <br><br>
                <label for="editOrganizerName">Organizer Name:</label>
                <input type="text" id="editOrganizerName" name="editOrganizerName"  value="<?php echo htmlspecialchars($volunteerDetail['organizer_name']); ?>" required>
                <br><br>
                <label for="organizerContact">Organizer Contact (Email):</label>
                <input type="text" id="organizerContact" name="organizerContact" value="<?php echo htmlspecialchars($volunteerDetail['organizer_contact']); ?>" required>
                <br><br>
                <label for="organizerPhone">Organizer Contact (Phone):</label>
                <input type="text" id="organizerPhone" name="organizerPhone"  value="<?php echo htmlspecialchars($volunteerDetail['organizer_number']); ?>" required>
                <br><br>
                <label for="editVolunteerDate">Event Date:</label>
                <input type="date" id="editVolunteerDate" name="editVolunteerDate" value="<?php echo htmlspecialchars($volunteerDetail['date']); ?>" required>
                <br><br>
                <label for="editVolunteerTime">Event Time:</label>
                <input type="time" id="editVolunteerTime" name="editVolunteerTime"  value="<?php echo htmlspecialchars($volunteerDetail['event_time']); ?>" required>
                <br><br>
                <label for="editVolunteerLocation">Event Location:</label>
                <input type="text" id="editVolunteerLocation" name="editVolunteerLocation" value="<?php echo htmlspecialchars($volunteerDetail['location']); ?>" required>
                <br><br>
                <label for="editVolunteerDescription">Event Description:</label>
                <textarea id="editVolunteerDescription" name="editVolunteerDescription" required><?php echo htmlspecialchars($volunteerDetail['description']); ?></textarea>
                <br><br>
                <label for="editVolunteerRequirement">Event Requirements:</label>
                <input type="text" id="editVolunteerRequirement" name="editVolunteerRequirement" value="<?php echo htmlspecialchars($volunteerDetail['requirement']); ?>" required>
                <br><br>
                <label for="editMaxVolunteers">Max Participants:</label>
                <input type="number" id="editMaxVolunteers" name="editMaxVolunteers" value="<?php echo htmlspecialchars($volunteerDetail['max_volunteer']); ?>" required>
                <br><br>
                <label for="editVolunteerImage">Event Image:</label>
                <input type="file" id="editVolunteerImage" name="editVolunteerImage" accept="image/*">
                
                <br><br>
                <button type="submit" class="submit-btn">Save Changes</button>
            </form>
        </div>
        <div class="modal-right">
        <input type="hidden" id="existingVolunteerImages" name="existingVolunteerImages" value="<?php echo base64_encode($volunteerDetail['image']); ?>">
                <div>
                    <label>Current Event Image:</label>
                    <?php if (!empty($volunteerDetail['image'])): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($volunteerDetail['image']); ?>" alt="Event Image" style="max-width: 100%; height: auto;">
                    <?php else: ?>
                        <p>No image available.</p>
                    <?php endif; ?>
                </div>
        </div>
    </div>
</div>

<!---Edit Event Modal --->
<div class="modal" id="editParticipantModal" style="display: <?php echo $eventDetail ? 'flex' : 'none'; ?>;">
    <div class="modal-content">
        <div class="modal-left">
            <span class="close-btn" id="closeEditParticipantModal">&times;</span>
            <h2>Edit Participant Event</h2>
            <br>
            <form id="editParticipantEventForm" method="POST" action="update_event.php" enctype="multipart/form-data">
                <input type="hidden" name="editEventId" value="<?php echo $eventDetail['id']; ?>">
                <label for="editEventName">Event Name:</label>
                <input type="text" id="editEventName" name="editEventName" value="<?php echo htmlspecialchars($eventDetail['title']); ?>" required>
                <br><br>
                <label for="editOrganizerName">Organizer Name:</label>
                <input type="text" id="editOrganizerName" name="editOrganizerName"  value="<?php echo htmlspecialchars($eventDetail['organizer_name']); ?>" required>
                <br><br>
                <label for="organizerContact">Organizer Contact (Email):</label>
                <input type="text" id="organizerContact" name="organizerContact" value="<?php echo htmlspecialchars($eventDetail['organizer_contact']); ?>" required>
                <br><br>
                <label for="organizerPhone">Organizer Contact (Phone):</label>
                <input type="text" id="organizerPhone" name="organizerPhone"  value="<?php echo htmlspecialchars($eventDetail['organizer_number']); ?>" required>
                <br><br>
                <label for="editEventDate">Event Date:</label>
                <input type="date" id="editEventDate" name="editEventDate" value="<?php echo htmlspecialchars($eventDetail['date']); ?>" required>
                <br><br>
                <label for="editEventTime">Event Time:</label>
                <input type="time" id="editEventTime" name="editEventTime"  value="<?php echo htmlspecialchars($eventDetail['event_time']); ?>" required>
                <br><br>
                <label for="startRegDate">Start Registration Date:</label>
                <input type="date" id="startRegDate" name="startRegDate" value="<?php echo htmlspecialchars($eventDetail['start_registration_date']); ?>" required>
                <br><br>
                <label for="dueRegDate">Due Registration Date:</label>
                <input type="date" id="dueRegDate" name="dueRegDate" value="<?php echo htmlspecialchars($eventDetail['due_registration_date']); ?>" required>
                <br><br>
                <label for="editEventLocation">Event Location:</label>
                <input type="text" id="editEventLocation" name="editEventLocation" value="<?php echo htmlspecialchars($eventDetail['location']); ?>" required>
                <br><br>
                <label for="editEventDescription">Event Description:</label>
                <textarea id="editEventDescription" name="editEventDescription" required><?php echo htmlspecialchars($eventDetail['description']); ?></textarea>
                <br><br>
                <label for="eventCategory">Event Category:</label>
                <select class="form-control" id="eventCategory" name="eventCategory" required>
                    <option value="">Select One</option>
                    <option value="Community Clean-Up" <?php if ($eventDetail['event_category'] == 'Community Clean-Up') echo 'selected'; ?>>Community Clean-Up</option>
                    <option value="Skills Sharing Session" <?php if ($eventDetail['event_category'] == 'Skills Sharing Session') echo 'selected'; ?>>Skills Sharing Session</option>
                    <option value="Charity Event" <?php if ($eventDetail['event_category'] == 'Charity Event') echo 'selected'; ?>>Charity Event</option>
                </select>
                <br><br>
                <label for="editMaxParticipants">Max Participants:</label>
                <input type="number" id="editMaxParticipants" name="editMaxParticipants" value="<?php echo htmlspecialchars($eventDetail['max_participants']); ?>" required>
                <br><br>
                <label for="editEventImage">Event Image:</label>
                <input type="file" id="editEventImage" name="editEventImage" accept="image/*">
                <br><br>
                <button type="submit" class="submit-btn">Save Changes</button>
            </form>
        </div>
        <!-- Modal Right Side for Image Preview -->
        <div class="modal-right" style="display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 20px;">
            <img id="editEventImagePreview" src="" alt="Event Image Preview" style="display: none; max-width: 300px; height: 400px;"> <!-- Initially hidden -->

            <div>
                <label>Current Event Image:</label>
                <?php if (!empty($eventDetail['image'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($eventDetail['image']); ?>" alt="Event Image" style="max-width: 100%; height: auto;">
                <?php else: ?>
                    <p>No image available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    </div>
</div>


<!-- Delete Event Confirmation Modal -->
<div class="modal" id="deleteEventModal">
    <div class="modal-content">
        <span class="close-btn" id="closeDeleteModal">&times;</span>
        <h2>Are you sure you want to delete this event?</h2>
        <button class="submit-btn" id="confirmDeleteBtn">Yes, Delete</button>
        <button class="submit-btn" id="cancelDeleteBtn">Cancel</button>
    </div>
</div>

<!-- Delete Volunteer Confirmation Modal -->
<div class="modal" id="deleteVolunteerModal">
    <div class="modal-content">
        <span class="close-btn" id="closeVolunteerDeleteModal">&times;</span>
        <h2>Are you sure you want to delete this event?</h2>
        <button class="submit-btn" id="confirmVolunteerDeleteBtn">Yes, Delete</button>
        <button class="submit-btn" id="cancelVolunteerDeleteBtn">Cancel</button>
    </div>
</div>

<!-- View Event Details Modal -->
<div class="modal" id="viewEventModal" style="display: <?php echo $eventDetails ? 'flex' : 'none'; ?>;">
    <div class="modal-content">
        <span class="close-btn" id="closeViewModal">&times;</span>
        <h2>Event Details</h2>
        <div class="modal-right">
            <?php if ($eventDetails): ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($eventDetails['image']); ?>" alt="Event Image">
            <?php else: ?>
                <img src="../images/event_image_placeholder.jpg" alt="Event Image">
            <?php endif; ?>
        </div>
        <div class="modal-left">
            <p><strong>Event Name:</strong> <span id="viewEventName"><?php echo $eventDetails['title']; ?></span></p>
            <p><strong>Organizer Name:</strong><span id="viewEventOrganizerName"><?php echo $eventDetails['organizer_name']; ?></p>
            <p><strong>Organizer Contact (Email):</strong><span id="viewEventOrganizerContact"><?php echo $eventDetails['organizer_contact']; ?></span></p>
            <p><strong>Organizer Contact (Phone):</strong><span id="viewEventOrganizerPhone"><?php echo $eventDetails['organizer_number']; ?></p>
            <p><strong>Event Date:</strong> <span id="viewEventDate"><?php echo $eventDetails['date']; ?></span></p>
            <p><strong>Event Time:</strong><span id="viewEventTime"><?php echo $eventDetails['event_time']; ?></p>
            <p><strong>Registration date:</strong> <span id="viewEventDate"><?php echo $eventDetails['start_registration_date']; ?></span></p>
            <p><strong>Registration due date:</strong> <span id="viewEventDate"><?php echo $eventDetails['due_registration_date']; ?></span></p>
            <p><strong>Event Category:</strong> <span id="viewEventDate"><?php echo $eventDetails['event_category']; ?></span></p>
            <p><strong>Event Location:</strong> <span id="viewEventLocation"><?php echo $eventDetails['location']; ?></span></p>
            <p><strong>Event Description:</strong><?php echo $eventDetails['description']; ?></p>
            <p><strong>Max Participants:</strong> <span id="viewEventDate"><?php echo $eventDetails['max_participants']; ?></span></p>

        </div>
    </div>
</div>

<div class="modal" id="viewVolunteerModal" style="display: <?php echo $volunteerDetails ? 'flex' : 'none'; ?>;">
    <div class="modal-content">
        <span class="close-btn" id="closeViewVolunteerModal">&times;</span>
        <h2>Event Details</h2>
        <div class="modal-right">
            <?php if ($volunteerDetails): ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($volunteerDetails['image']); ?>" alt="Event Image">
            <?php else: ?>
                <img src="../images/event_image_placeholder.jpg" alt="Event Image">
            <?php endif; ?>
        </div>
        <div class="modal-left">
            <p><strong>Event Name:</strong> <span id="viewEventName"><?php echo $volunteerDetails['title']; ?></span></p>
            <p><strong>Organizer Name:</strong><span id="viewEventOrganizerName"><?php echo $volunteerDetails['organizer_name']; ?></p>
            <p><strong>Organizer Contact (Email):</strong><span id="viewEventOrganizerContact"><?php echo $volunteerDetails['organizer_contact']; ?></span></p>
            <p><strong>Organizer Contact (Phone):</strong><span id="viewEventOrganizerPhone"><?php echo $volunteerDetails['organizer_number']; ?></p>
            <p><strong>Event Date:</strong> <span id="viewEventDate"><?php echo $volunteerDetails['date']; ?></span></p>
            <p><strong>Event Time:</strong><span id="viewEventTime"><?php echo $volunteerDetails['event_time']; ?></p>
            <p><strong>Event Location:</strong> <span id="viewEventLocation"><?php echo $volunteerDetails['location']; ?></span></p>
            <p><strong>Event Description:</strong><?php echo $volunteerDetails['description']; ?></p>
            <p><strong>Max Volunteers:</strong> <span id="viewEventDate"><?php echo $volunteerDetails['max_volunteer']; ?></span></p>
            <p><strong>Requirements:</strong> <span id="viewEventDate"><?php echo $volunteerDetails['requirement']; ?></span></p>


        </div>
    </div>
</div>



<!-- Pagination Control -->
<div class="pagination">
    <span id="current">Page 1 of 10</span>
</div>
<br>
    <!--back to top button-->
    <a href="#" id="back-to-top" title="Back to Top">&uarr;</a>
    <script src="../js/script.js"></script>
<!-- Footer Section -->
 
<?php include('footer.php'); ?>


</body>
</html>