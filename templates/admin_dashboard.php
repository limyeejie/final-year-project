<?php
include 'session_check.php';
check_if_organizer();
check_if_student();
check_user_logged_in();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/admindashboard_styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
</head>

<body>
    <!-- Header Section -->
    <?php include('admin_header.php'); ?>

    <!-- Main Dashboard Section -->
    <section class="dashboard-container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <h1>Welcome to the Admin Dashboard</h1>
            <button class="generate-report-btn">Generate Report</button>
        </div>


        <div class="section-card" style="display: none;">
            <h2>Overall Performance</h2>
            <br>
            <div class="performance-metrics">
                <div class="metric-box">
                    <h3>Total Organisers</h3>
                    <p id="total-organisers">0</p>
                </div>
                <div class="metric-box">
                    <h3>Total Students</h3>
                    <p id="total-participants">0</p>
                </div>
                <div class="metric-box">
                    <h3>Total Events Available</h3>
                    <p id="total-events">0</p>
                </div>
            </div>
        </div>

        <!-- Overall Performance Section -->
        <div class="section-card">
            <h2>Overall Performance</h2>
            <div class="performance-metrics">
                <div class="chart-box">
                    <h3>Organisers & Students</h3>
                    <canvas id="organisersStudentsChart"></canvas>
                </div>
                <div class="chart-box">
                    <h3>Events & Volunteers</h3>
                    <canvas id="eventsVolunteersChart"></canvas>
                </div>
                <div class="chart-box">
                    <h3>Participants & Volunteers</h3>
                    <canvas id="registeredChart"></canvas>
                </div>
            </div>
        </div>


        <!-- User Management Section -->
        <br>
        <div class="section-card">
            <h2>User Management</h2>
            <div class="search-bar">
                <input type="text" placeholder="Search by Keywords" class="search-input-user" aria-label="Search Users">
                <button class="search-btn" aria-label="Search"><i class="fas fa-search"></i></button>
                <label for="results-per-page-user">Results per page:</label>
                <select id="results-per-page-user" class="results-per-page" aria-label="Results per page">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                </select>
                <button class="prev-page-user" aria-label="Previous Page" disabled>Prev</button>
                <span class="page-counter-user">Page 1 of 1</span>
                <button class="next-page-user" aria-label="Next Page">Next</button>
            </div>
            <div class="table-container">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dynamic User Data Will be Rendered Here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Edit User Modal -->
        <div class="modal" id="editUserModal">
            <div class="modal-content">
                <h3>Edit User</h3>
                <form id="editUserForm">
                    <label for="editUserName">Name</label>
                    <input type="text" id="editUserName" name="name" required>

                    <label for="editUserEmail">Email</label>
                    <input type="email" id="editUserEmail" name="email" required>

                    <label for="editUserRole">Role</label>
                    <select id="editUserRole" name="role" required>
                        <option value="Student">Student</option>
                        <option value="Organizer">Organizer</option>
                        <option value="Admin">Admin</option>
                    </select>

                    <label for="editUserContactNumber">Contact Number</label>
                    <input type="text" id="editUserContactNumber" name="contact_number" required>

                    <label for="editUserDateOfBirth">Date of Birth</label>
                    <input type="date" id="editUserDateOfBirth" name="date_of_birth" required>

                    <label for="editUserGender">Gender</label>
                    <select id="editUserGender" name="gender" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>

                    <div class="modal-actions">
                        <button type="button" class="cancel-btn" onclick="closeEditModal()">Cancel</button>
                        <button type="button" class="delete-btn" onclick="deleteUser()">Delete</button>
                        <button type="submit" id="saveUserButton" class="save-btn">Save</button>
                    </div>


                </form>
            </div>
        </div>

        <!-- Event Section -->
        <br>
        <div class="section-card">
            <h2>Event Management</h2>
            <div class="search-bar">
                <input type="text" placeholder="Search by Keywords" class="search-input-event" aria-label="Search Events">
                <button class="search-btn" aria-label="Search"><i class="fas fa-search"></i></button>
                <label for="results-per-page-event">Results per page:</label>
                <select id="results-per-page-event" class="results-per-page" aria-label="Results per page">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                </select>
                <button class="prev-page-event" aria-label="Previous Page" disabled>Prev</button>
                <span class="page-counter-event">Page 1 of 1</span>
                <button class="next-page-event" aria-label="Next Page">Next</button>
            </div>
            <div class="table-container">
                <table class="event-table">
                    <thead>
                        <tr>
                            <th>Event ID</th>
                            <th>Event Name</th>
                            <th>Organizer</th>
                            <th>Date</th>
                            <th>Action</th> <!-- Action Column -->
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dynamic Event Data Will be Rendered Here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Edit Event Modal -->
        <div id="editEventModal" class="modal">
            <div class="modal-content">
                <h3>Edit Event</h3>
                <form id="editEventForm">
                    <label for="editEventName">Event Name</label>
                    <input type="text" id="editEventName" name="name" required>

                    <label for="editEventOrganizerName">Organizer Name</label>
                    <input type="text" id="editEventOrganizerName" name="organizerName" required>

                    <label for="editEventOrganizerEmail">Organizer Email</label>
                    <input type="text" id="editEventOrganizerEmail" name="organizerEmail" required>

                    <label for="editEventOrganizerNumber">Organizer Phone</label>
                    <input type="text" id="editEventOrganizerNumber" name="organizerNumber" required>

                    <label for="editEventDate">Event Date</label>
                    <input type="date" id="editEventDate" name="date" required>

                    <label for="editEventDate">Event Time</label>
                    <input type="time" id="editEventTime" name="time" required>

                    <label for="editEventStartRegDate">Start Registration Date</label>
                    <input type="date" id="editEventStartRegDate" name="start_reg_date" required>

                    <label for="editEventDueRegDate">Due Registration Date</label>
                    <input type="date" id="editEventDueRegDate" name="due_reg_date" required>

                    <label for="editEventLocation">Location</label>
                    <input type="text" id="editEventLocation" name="location" required>

                    <label for="editEventDescription">Description</label>
                    <textarea id="editEventDescription" name="description" required></textarea>

                    <label for="editEventCategory">Category</label>
                    <select id="editEventCategory" name="eventCategory" required>
                        <option value="">Select Category</option>
                        <option value="Community Clean-Up">Community Clean-Up</option>
                        <option value="Skills Sharing Session">Skills Sharing Session</option>
                        <option value="Charity Event">Charity Event</option>
                    </select>

                    <label for="editEventMaxParticipants">Max Participants</label>
                    <input type="number" id="editEventMaxParticipants" name="max_participants" required>

                    <div class="modal-actions">
                        <button type="button" class="cancel-btn" onclick="closeEditEventModal()">Cancel</button>
                        <button type="button" class="delete-btn" onclick="deleteEvent()">Delete</button>
                        <button type="submit" id="saveEventButton" class="save-btn">Save</button>
                    </div>

                </form>
            </div>
        </div>


        <!-- Volunteer Management Section -->
        <br>
        <div class="section-card">
            <h2>Volunteer Management</h2>
            <div class="search-bar">
                <input type="text" placeholder="Search by Keywords" class="search-input-volunteer" aria-label="Search Volunteers">
                <button class="search-btn" aria-label="Search"><i class="fas fa-search"></i></button>
                <label for="results-per-page-volunteer">Results per page:</label>
                <select id="results-per-page-volunteer" class="results-per-page" aria-label="Results per page">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                </select>
                <button class="prev-page-volunteer" aria-label="Previous Page" disabled>Prev</button>
                <span class="page-counter-volunteer">Page 1 of 1</span>
                <button class="next-page-volunteer" aria-label="Next Page">Next</button>
            </div>
            <div class="table-container">
                <table class="volunteer-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Description</th>
                            <th>Requirement</th>
                            <th>Max Volunteers</th>
                            <th>Organizer Contact</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dynamic Volunteer Data Will Be Rendered Here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Edit Volunteer Modal -->
        <div id="editVolunteerModal" class="modal">
            <div class="modal-content">
                <h3>Edit Volunteer Information</h3>
                <form id="editVolunteerForm">
                    <label for="editVolunteerTitle">Title</label>
                    <input type="text" id="editVolunteerTitle" name="title" required>

                    <label for="editVolunteerDate">Date</label>
                    <input type="date" id="editVolunteerDate" name="date" required>

                    <label for="editVolunteerTime">Event Time</label>
                    <input type="time" id="editVolunteerTime" name="editVolunteerTime" required>

                    <label for="editVolunteerLocation">Location</label>
                    <input type="text" id="editVolunteerLocation" name="location" required>

                    <label for="editVolunteerDescription">Description</label>
                    <textarea id="editVolunteerDescription" name="description" required></textarea>

                    <label for="editVolunteerRequirement">Requirement</label>
                    <textarea id="editVolunteerRequirement" name="requirement" required></textarea>

                    <label for="editVolunteerMaxVolunteers">Max Volunteers</label>
                    <input type="number" id="editVolunteerMaxVolunteers" name="max_volunteer" required>

                    <label for="editOrganizerName">Organizer Name</label>
                    <input type="text" id="editOrganizerName" name="organizer_name" required>
                    
                    <label for="editOrganizerNumber">Organizer Contact (Phone)</label>
                    <input type="text" id="editOrganizerNumber" name="organizer_number" required>

                    <label for="editVolunteerContact">Organizer Contact (Email)</label>
                    <input type="text" id="editVolunteerContact" name="organizer_contact" required>

                    <div class="modal-actions">
                        <button type="button" class="cancel-btn" onclick="closeEditVolunteerModal()">Cancel</button>
                        <button type="button" class="delete-btn" onclick="deleteVolunteer()">Delete</button>
                        <button type="submit" id="saveVolunteerButton" class="save-btn">Save</button>
                    </div>

                </form>
            </div>
        </div>


        </form>
        </div>
        </div>

        <!-- Contact Us Section -->
        <br>
        <div class="section-card">
            <h2>Contact Us</h2>
            <!-- Search and Pagination Controls for Contact Us -->
            <div class="search-bar">
                <input type="text" placeholder="Search by Keywords" class="search-input-contact" aria-label="Search Contact Us">
                <button class="search-btn" aria-label="Search"><i class="fas fa-search"></i></button>
                <label for="results-per-page-contact">Results per page:</label>
                <select id="results-per-page-contact" class="results-per-page" aria-label="Results per page">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                </select>
                <button class="prev-page-contact" aria-label="Previous Page" disabled>Prev</button>
                <span class="page-counter-contact">Page 1 of 1</span>
                <button class="next-page-contact" aria-label="Next Page">Next</button>
            </div>
            <!-- Contact Us Table -->
            <div class="table-container">
                <table class="contact-us-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Reason</th>
                            <th>Role</th>
                            <th>Message</th>
                            <th>Action</th> <!-- Added Action column -->
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dynamic Contact Us Data Will be Rendered Here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Edit Contact Us Modal -->
        <div id="editContactModal" class="modal">
            <div class="modal-content">
                <h3>Edit Contact Us</h3>
                <form id="editContactForm">
                    <label for="editContactFullname">Full Name</label>
                    <input type="text" id="editContactFullname" name="fullname" required />

                    <label for="editContactEmail">Email</label>
                    <input type="email" id="editContactEmail" name="email" required />

                    <label for="editContactSubject">Subject</label>
                    <input type="text" id="editContactSubject" name="subject" required />

                    <label for="editContactReason">Reason</label>
                    <input type="text" id="editContactReason" name="reason" required />

                    <label for="editContactRole">Role</label>
                    <input type="text" id="editContactRole" name="role" required />

                    <label for="editContactMessage">Message</label>
                    <textarea id="editContactMessage" name="message" required></textarea>

                    <div class="modal-actions">
                        <button type="button" class="cancel-btn" onclick="closeEditContactModal()">Cancel</button>
                        <button type="button" class="delete-btn" onclick="deleteContact()">Delete</button>
                        <button type="submit" id="saveContactButton" class="save-btn">Save</button>
                    </div>

                </form>
            </div>
        </div>
        <br><br>

        <!-- Footer Section -->
        <footer class="footer">
            <div class="footer-content">
                <!-- Navigation Links -->
                <nav class="footer-nav">
                    <li><a href="about_us.php" class="nav-link"><i class="fa fa-info-circle"></i> About Us</a></li>
                    <li><a href="faqs.php" class="nav-link"><i class="fa fa-question-circle"></i> FAQs</a></li>
                    <li><a href="privacy.php" class="nav-link"><i class="fa fa-user-secret"></i> Privacy Policy</a></li>
                </nav>

                <!-- Copyright Text -->
                <p class="footer-text">&copy; 2024 SphereConnect. All rights reserved.</p>
            </div>
        </footer>


        <!-- JavaScript file -->
        <?php include('modal.php'); ?>
        <script src="../js/modal.js"></script>
        <script src="../js/dashboard.js"></script>
        <!-- Pie Chart JavaScript -->
        <script src="../js/admin_script.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            const {jsPDF} = window.jspdf;

            document.querySelector('.generate-report-btn').addEventListener('click', generateReport);

            function generateReport() {
                // Initialize jsPDF with landscape orientation
                const doc = new jsPDF('landscape');

                // Report title
                doc.setFontSize(20);
                doc.text('Admin Dashboard Report', 20, 20);

                // Overall Performance metrics
                doc.setFontSize(16);
                doc.text('Overall Performance', 20, 40);
                doc.setFontSize(12);
                let yOffset = 50;

                const metrics = [{
                        label: 'Total Organisers',
                        value: document.getElementById('total-organisers').textContent || '0'
                    },
                    {
                        label: 'Total Participants',
                        value: document.getElementById('total-participants').textContent || '0'
                    },
                    {
                        label: 'Total Events',
                        value: document.getElementById('total-events').textContent || '0'
                    }
                ];

                metrics.forEach(metric => {
                    doc.text(`${metric.label}: ${metric.value}`, 20, yOffset);
                    yOffset += 10;
                });

                // User Management Section
                yOffset += 20;
                doc.setFontSize(16);
                doc.text('User Management', 20, yOffset);
                doc.setFontSize(12);
                yOffset += 10;

                const userRows = document.querySelectorAll('.user-table tbody tr');
                const maxWidth = 250; // Adjust width to fit layout, avoid the content run out from document

                userRows.forEach(row => {
                    const columns = Array.from(row.cells).map(cell => cell.textContent);
                    const wrappedText = doc.splitTextToSize(`ID: ${columns[0]}, Name: ${columns[1]}, Email: ${columns[2]}, Role: ${columns[3]}`, maxWidth);

                    wrappedText.forEach(line => {
                        doc.text(line, 20, yOffset);
                        yOffset += 10;

                        // Add new page if necessary
                        if (yOffset > 190) {
                            doc.addPage('landscape');
                            yOffset = 20;
                        }
                    });
                });

                // Event Section
                yOffset += 20;
                doc.setFontSize(16);
                doc.text('Event', 20, yOffset);
                doc.setFontSize(12);
                yOffset += 10;

                const eventRows = document.querySelectorAll('.event-table tbody tr');
                eventRows.forEach(row => {
                    const columns = Array.from(row.cells).map(cell => cell.textContent);
                    const wrappedText = doc.splitTextToSize(`ID: ${columns[0]}, Event Name: ${columns[1]}, Organizer: ${columns[2]}, Date: ${columns[3]}`, maxWidth);

                    wrappedText.forEach(line => {
                        doc.text(line, 20, yOffset);
                        yOffset += 10;

                        if (yOffset > 190) {
                            doc.addPage('landscape');
                            yOffset = 20;
                        }
                    });
                });

                // Volunteer Management Section
                yOffset += 20;
                doc.setFontSize(16);
                doc.text('Volunteer Management', 20, yOffset);
                doc.setFontSize(12);
                yOffset += 10;

                const volunteerRows = document.querySelectorAll('.volunteer-table tbody tr');
                volunteerRows.forEach(row => {
                    const columns = Array.from(row.cells).map(cell => cell.textContent);
                    const wrappedText = doc.splitTextToSize(`ID: ${columns[0]}, Title: ${columns[1]}, Date: ${columns[2]}, Location: ${columns[3]}, Description: ${columns[4]}`, maxWidth);

                    wrappedText.forEach(line => {
                        doc.text(line, 20, yOffset);
                        yOffset += 10;

                        if (yOffset > 190) {
                            doc.addPage('landscape');
                            yOffset = 20;
                        }
                    });
                });

                // Contact Us Section
                yOffset += 20;
                doc.setFontSize(16);
                doc.text('Contact Us', 20, yOffset);
                doc.setFontSize(12);
                yOffset += 10;

                const contactRows = document.querySelectorAll('.contact-us-table tbody tr');
                contactRows.forEach(row => {
                    const columns = Array.from(row.cells).map(cell => cell.textContent);
                    const wrappedText = doc.splitTextToSize(`ID: ${columns[0]}, Full Name: ${columns[1]}, Email: ${columns[2]}, Subject: ${columns[3]}, Reason: ${columns[4]}, Role: ${columns[5]}, Message: ${columns[6]}`, maxWidth);

                    wrappedText.forEach(line => {
                        doc.text(line, 20, yOffset);
                        yOffset += 10;

                        if (yOffset > 190) {
                            doc.addPage('landscape');
                            yOffset = 20;
                        }
                    });
                });

                // Save the PDF
                doc.save('Admin_Dashboard_Report.pdf');
            }
        </script>


</body>

</html>