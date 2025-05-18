<?php
include 'modal.php';
include 'session_check.php';
include '../config/connection.php';

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT full_name, email, role, date_of_birth, contact_number, gender FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found";
    }
    $stmt->close();
} else {
    header("Location: login_form.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $fullName = $_POST['name'];
    $role = $_SESSION['role'];
    $email = $_POST['email'];
    $contactNumber = $_POST['contact'];
    $dateOfBirth = $_POST['dob'];
    $password = $_POST['password'];

    if (empty($fullName) || preg_match('/[0-9]/', $fullName)) {

        $redirectUrl = 'profile.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Full Name is required and cannot contain numbers');
        </script>";
        exit();
    } elseif (!preg_match("/^[a-zA-Z\s'-]+$/", $fullName)) {

        $redirectUrl = 'profile.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Full Name can only contain letters, spaces, hyphens, and apostrophes.');
        </script>";
        exit();
    }

    if (empty($email)) {

        $redirectUrl = 'profile.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Email is required');
        </script>";
        exit();
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Invalid email format');
        </script>";
        exit();
    } else {
        $emailCheckStmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $emailCheckStmt->bind_param("si", $email, $userId);
        $emailCheckStmt->execute();
        $emailCheckStmt->store_result();
        if ($emailCheckStmt->num_rows > 0) {
            $errors['email'] = "Email already exists. Please use a different email.";
        }
        $emailCheckStmt->close();
    }

    if (empty($contactNumber)) {
        $redirectUrl = 'profile.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Contact Number is required.');
        </script>";
        exit();
    } elseif (!preg_match("/^\+60\d{9,10}$/", $contactNumber)) {

        $redirectUrl = 'profile.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Contact Number must start with +60 and be followed by 9 or 10 digits.');
        </script>";
        exit();
    }
    
    if (empty($dateOfBirth)) {
        $redirectUrl = 'profile.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Date of Birth is required.');
        </script>";
        exit();
    } else {
        $dob = new DateTime($dateOfBirth);
        $currentDate = new DateTime();
        $age = $currentDate->diff($dob)->y;
    
        if ($dob > $currentDate) {
            $redirectUrl = 'profile.php';
            echo "<script>
            document.querySelector('#failureModal .modal-close-btn')
                .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
            showModal('failure', 'Date of Birth cannot be in the future.');
            </script>";
            exit();
        } elseif ($age < 18) {
            $redirectUrl = 'profile.php';
            echo "<script>
            document.querySelector('#failureModal .modal-close-btn')
                .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
            showModal('failure', 'You must be at least 18 years old to update your profile.');
            </script>";
            exit();
        }
    }
    

    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();
    $stmt->close();

    

    if (password_verify($password, $hashedPassword)) {
        $stmt = $conn->prepare("UPDATE users SET full_name = ?, role = ?, contact_number = ?, date_of_birth = ?, email = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $fullName, $role, $contactNumber, $dateOfBirth, $email, $userId);
        if ($stmt->execute()) {
            $_SESSION['full_name'] = $fullName;
            $_SESSION['role'] = $role;
            $_SESSION['email'] = $email;
            $_SESSION['contact'] = $contactNumber;
            $_SESSION['dob'] = $dateOfBirth;
            $redirectUrl = 'profile.php';
            echo "<script>
            document.querySelector('#successModal .modal-close-btn')
                .setAttribute('onclick', `closeModal('successModal', '$redirectUrl')`);
            showModal('success', 'Account updated successfully.');
            </script>";
            exit();
        } else {
            echo "Error Updating profile";
        }

        $stmt->close();
    }
}

// My Reward Section - download badge
// Badge tracking array
$earnedBadges = [];

// Check total activities (events + volunteer activities)
$resultEvents = $conn->query("SELECT COUNT(*) as participation_count FROM participants WHERE userId = $userId AND attendance = 1");
$resultVolunteers = $conn->query("SELECT COUNT(*) as volunteer_count FROM application_volunteer WHERE userId = $userId AND attendance = 1");

$eventsRow = $resultEvents->fetch_assoc();
$volunteersRow = $resultVolunteers->fetch_assoc();

// Calculate total activities
$totalActivities = $eventsRow['participation_count'] + $volunteersRow['volunteer_count'];

// Check First Activity Badge
if ($totalActivities > 0) {
    $earnedBadges[] = 'first-event';
}

// Check Community Helper Badge
if ($totalActivities >= 5) {
    $earnedBadges[] = 'community-helper';
}

// Check Event Leader Badge
if ($totalActivities >= 10) {
    $earnedBadges[] = 'event-leader';
}

// Setting Change Password
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
unset($_SESSION['errors']);
$message = isset($_SESSION['message']) ? $_SESSION['message'] : [];
unset($_SESSION['message']);

// Setting Preferences
if (isset($user['role']) && $user['role'] == 'Student') {
    // Assume the user is already authenticated and you have the userId. 
    $userId = $_SESSION['user_id'];
    // Fetch user preferences from the database 
    $stmt = $conn->prepare("SELECT alert, news, recommend FROM profiles WHERE userId = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $profiles = $result->fetch_assoc();
    // Store preferences in $profiles array 
    // Check preferences and set checked variables 
    $checkedAlert = isset($profiles['alert']) && $profiles['alert'] ? 'checked' : '';
    $checkedNews = isset($profiles['news']) && $profiles['news'] ? 'checked' : '';
    $checkedRecommend = isset($profiles['recommend']) && $profiles['recommend'] ? 'checked' : '';
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/profile_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/purecss@2.0.6/build/pure-min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>
    <!-- Header Section -->
    <?php include('header.php'); ?>

    <!-- Profile Section -->
    <div class="profile">
        <div class="profile-picture">
            <?php if (isset($user['gender']) && $user['gender'] == 'Other'): ?>
                <img src="../images/profile/profile.png" alt="Profile Picture">
            <?php elseif (isset($user['gender']) && $user['gender'] == 'Female'): ?>
                <img src="../images/profile/femaleprofile.png" alt="Female Profile Picture">
            <?php elseif (isset($user['gender']) && $user['gender'] == 'Male'): ?>
                <img src="../images/profile/maleprofile.png" alt="Male Profile Picture">
            <?php else: ?>
                <img src="../images/profile/profile.png" alt="Profile Picture">
            <?php endif; ?>
        </div>

        <div class="profile-info">
            <h1>Welcome to your Profile, <br /><?php echo htmlspecialchars($user['full_name']); ?></h1>
            <div class="info">
                <div class="info">
                    <!-- Left Column -->
                    <p>Name: <?php echo htmlspecialchars($user['full_name']); ?></p>
                    <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
                    <p>Contact Number: <?php echo htmlspecialchars($user['contact_number']); ?></p>

                    <!-- Right Column -->
                    <p>Role: <?php echo htmlspecialchars($user['role']); ?></p>
                    <p>Date of Birth: <?php echo htmlspecialchars($user['date_of_birth']); ?></p>
                    <p>Gender: <?php echo htmlspecialchars($user['gender']); ?></p>
                </div>
            </div>
        </div>

        <button class="edit-btn">Edit</button>
    </div>
    <!-- Edit Info modal popup -->
    <div class="modal" id="editModal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Profile Information</h2>
            <form action="profile.php" method="POST">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $user['full_name']; ?>"><br><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>"><br><br>

                <label for="contact">Contact Number:</label>
                <input type="text" id="contact" name="contact" value="<?php echo $user['contact_number']; ?>"><br><br>

                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" value="<?php echo $user['date_of_birth']; ?>"><br><br>

                <label for="password">Password</label>
                <input type="password" id="password" name="password">

                <button class="submit-btn" type="submit">Save Changes</button>
            </form>
        </div>
    </div>


    <!-- Sidebar Section -->
    <div class="sidebar">
        <div class="profile-hamburger">&#9776;</div>
        <br>
        <br>
        <ul class="side-menu top">
            <li class="active">
                <a href="#" data-section="dashboard">
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <!--only student have my rewards and notification section-->
            <?php if (isset($user['role']) && $user['role'] == 'Student'): ?>
                <li>
                    <a href="#" data-section="my-rewards">
                        <span class="text">My Rewards</span>
                    </a>
                </li>
                <?php if (isset($profiles['alert']) && $profiles['alert'] == '1'): ?>
                    <li>
                        <a href="#" data-section="notifications">
                            <span class="text">Notifications</span>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
            <li>
                <a href="#" data-section="settings">
                    <span class="text">Settings</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Content -->
    <div class="content">
        <!-- DASHBOARD SECTION -->
        <div class="section dashboard" style="display: block;">
            <p class="content-title">Events</p>
            <div class="data-insight">

                <!-- Data Overview total event for Student or Organizer -->
                <?php if ($user['role'] == 'Student') : ?>
                    <!-- Student Donut Chart -->
                    <div class="data-total">
                        <div class="middle">
                            <div class="left">
                                <h3>Student Overview</h3>
                                <canvas id="studentOverviewDonut"></canvas>
                            </div>
                        </div>
                    </div>
                <?php elseif ($user['role'] == 'Organizer') : ?>
                    <!-- Organizer Donut Chart -->
                    <div class="data-total">
                        <div class="middle">
                            <div class="left">
                                <h3>Organiser Overview</h3>
                                <canvas id="organizerOverviewDonut"></canvas>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Upcoming Events Section (Common for both Student and Organizer) -->
                <div class="data-upcoming">
                    <h3>Upcoming Events Overview</h3>
                    <canvas id="upcomingEventsDonutChart"></canvas>
                </div>
            </div>

            <!-- Upcoming Event -->
            <?php if ($user['role'] == 'Student') : ?>
                <h2>Upcoming Event</h2>
                <div class="upcoming-event-evt">
                    <div id="upcomingEvtContainer" class="card-list">

                    </div>
                </div>
                <!-- Event Detail Modal -->
                <div class="event-detail-modal" id="eventDetailModal" style="display: none;">
                    <div class="modal-upcoming-content">
                        <span class="close-modal" id="closeUpcomingModal">&times;</span>
                        <h3 id="upcomingEventName"></h3>
                        <p id="upcomingEventLocation"></p>
                        <p id="upcomingEventDate"></p>
                        <p id="upcomingEventOrganizerName"></p>
                        <p id="upcomingEventOrganizerContact"></p>
                        <p id="upcomingEventOrganizerNumber"></p>
                        <p id="upcomingMaxParticipants"></p>
                    </div>
                </div>
            <?php elseif ($user['role'] == 'Organizer') : ?>
                <h2>Upcoming Event Overview</h2>
                <div class="upcoming-event-evt">
                    <div id="evtOverviewContainer" class="card-list">

                    </div>
                </div>
                <!-- Event Detail Modal -->
                <div class="event-detail-modal" id="eventDetailModal" style="display: none;">
                    <div class="modal-upcoming-content">
                        <span class="close-modal" id="closeUpcomingModal">&times;</span>
                        <h3 id="upcomingEventName"></h3>
                        <p id="upcomingEventLocation"></p>
                        <p id="upcomingEventDate"></p>
                        <p id="upcomingEventOrganizerContact"></p>
                        <p id="upcomingMaxParticipants"></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- past event review and feedback container -->
            <!-- Check user role -->
            <?php if ($user['role'] == 'Student') : ?>
                <!-- Past Event Review for Students -->
                <?php if (isset($profiles['news']) && $profiles['news'] == '1'): ?>
                    <h2>Past Event Review</h2>
                    <div class="past-event-review-evt">
                        <div id="pastEvtContainer" class="card-list">
                            <!-- Event review items will be dynamically populated here -->
                        </div>
                    </div>
                <?php endif; ?>
            <?php elseif ($user['role'] == 'Organizer') : ?>
                <!-- Feedbacks container for Organizers -->
                <h2>Feedbacks</h2>
                <div class="feedbacks-container">
                    <div id="feedbacksList" class="card-list">
                        <!-- Feedback items will be dynamically populated here -->
                    </div>
                </div>

            <?php endif; ?>
            <!-- Feedback form -->
            <div class="wrapper" id="feedbackModal">
                <div class="wrapper-content">
                    <span class="close" id="closeModal">&times;</span>
                    <h3 class="event-name">Event Feedback</h3>
                    <input type="hidden" class="event-id" value="">
                    <div class="rating">
                        <input type="number" name="rating" hidden>
                        <i class='bx bx-star star' style="--i: 0;"></i>
                        <i class='bx bx-star star' style="--i: 1;"></i>
                        <i class='bx bx-star star' style="--i: 2;"></i>
                        <i class='bx bx-star star' style="--i: 3;"></i>
                        <i class='bx bx-star star' style="--i: 4;"></i>
                    </div>
                    <textarea name="comment" id="comment" cols="15" rows="5" placeholder="Enter your opinions here..."></textarea>
                    <div class="btn-group">
                        <button type="submit" class="btn submit">Send</button>
                        <button type="reset" class="btn cancel">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MY REWARDS SECTION -->
        <?php if (isset($user['role']) && $user['role'] == 'Student'): ?>
            <div class="section my-rewards" style="display: none;">
                <p class="content-title">My Rewards</p>

                <!-- Display Achieved Reward -->
                <div class="rewards-section">
                    <h2>Your Achievements</h2>

                    <!-- Set when the user achieved then only can download -->
                    <!-- Display badges based on earned badges array -->
                    <div class="badges-container">
                        <?php if (in_array('first-event', $earnedBadges)): ?>
                            <div class="badge" id="first-event">
                                <h3>First Activity</h3>
                                <p>Awarded for completing your first activity (event or volunteer).</p>
                                <button class="download-btn">Download Badge</button>
                            </div>
                        <?php endif; ?>

                        <?php if (in_array('community-helper', $earnedBadges)): ?>
                            <div class="badge" id="community-helper">
                                <h3>Community Helper</h3>
                                <p>Earned for participating in multiple activities.</p>
                                <button class="download-btn">Download Badge</button>
                            </div>
                        <?php endif; ?>

                        <?php if (in_array('event-leader', $earnedBadges)): ?>
                            <div class="badge" id="event-leader">
                                <h3>Event Leader</h3>
                                <p>Earned by organizing multiple events.</p>
                                <button class="download-btn">Download Badge</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recommended activity -->
                <?php if (isset($profiles['recommend']) && $profiles['recommend'] == '1'): ?>
                    <div class="recommended-activities">
                        <h2>Recommended Activities for Next Badge</h2>
                        <!-- maybe can redirect to the event & volunteer pages -->
                        <div class="activities-container" id="activity-list">
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        <?php endif; ?>

        <!-- NOTIFICATIONS SECTION -->
        <?php if (isset($user['role']) && $user['role'] == 'Student'): ?>
            <?php if (isset($profiles['alert']) && $profiles['alert'] == '1'): ?>
                <div class="section notifications" style="display: none;">
                    <div class="notificationContainer">
                        <div class="header">
                            <div class="notificationHeader">
                                <h2>Notifications</h2>
                                <span id="num-of-notif"></span>
                            </div>
                            <p id="mark-as-read">Mark All As Read</p>
                        </div>
                        <div class="main">

                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- MY SETTINGS SECTION -->
        <div class="section settings" style="display: none;">
            <p class="content-title">Account Settings</p>

            <div class="settingContainer">
                <h2 class="setting-title">Change Password</h2>
                <form action="setting_chg_pw.php" method="POST">
                    <div class="card-body card-spacing">
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email">
                            <span class="error-message" style="color: red;"><?php echo isset($errors['email']) ? $errors['email'] : ''; ?></span>
                        </div>
                        <div class="form-group">
                            <label class="form-label">New password</label>
                            <input type="password" id="new-password" name="new-password" class="form-control" placeholder="Enter new password">
                            <span class="error-message" style="color: red;"><?php echo isset($errors['new-password']) ? $errors['new-password'] : ''; ?></span>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirm new password</label>
                            <input type="password" id="confirm-password" name="confirm-password" class="form-control" placeholder="Confirm new password">
                            <span class="error-message" style="color: red;"><?php echo isset($errors['confirm-password']) ? $errors['confirm-password'] : ''; ?></span>
                        </div>

                        <span class="error-message" style="color: red; text-align: center;"><?php echo isset($errors['password']) ? $errors['password'] : ''; ?></span>
                        <span class="error-message" style="color: black; text-align: center;"><?php echo isset($message['password']) ? $message['password'] : ''; ?></span>

                        <!-- Submit Button -->
                        <div style="display: flex; gap: 10px; justify-content:center;">
                            <button type="submit" style="padding: 6px 20px;">Change Password</button>
                        </div>
                    </div>

                </form>
                <!--Notifications-->
                <?php
                $checked = isset($_POST['alert']) || isset($_POST['news']) || isset($_POST['recommend']);
                ?>
                <?php if (isset($user['role']) && $user['role'] == 'Student'): ?>
                    <!--preferences-->
                    <h2 class="setting-title">Notifications</h2>
                    <div class="card-body card-spacing">
                        <h6 class="notification-title">Activity</h6>
                        <div class="form-group">
                            <label class="switcher">
                                <input type="checkbox" class="switcher-input event-alerts-toggle" name="event-alert" id="event-alerts-toggle" data-id="<?php echo $userId; ?>" <?php echo $checkedAlert; ?>>
                                <span class="switcher-indicator">
                                    <span class="switcher-yes"></span>
                                    <span class="switcher-no"></span>
                                </span>
                                <span class="switcher-label">Event Alerts and Reminders</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="switcher">
                                <input type="checkbox" class="switcher-input news-announcements-toggle" name="news" id="news-announcements-toggle" data-id="<?php echo $userId; ?>" <?php echo $checkedNews; ?>>
                                <span class="switcher-indicator">
                                    <span class="switcher-yes"></span>
                                    <span class="switcher-no"></span>
                                </span>
                                <span class="switcher-label">Past Event Reviews</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="switcher">
                                <input type="checkbox" class="switcher-input personalized-events-toggle" name="recommend" id="personalized-events-toggle" data-id="<?php echo $userId; ?>" <?php echo $checkedRecommend; ?>>
                                <span class="switcher-indicator">
                                    <span class="switcher-yes"></span>
                                    <span class="switcher-no"></span>
                                </span>
                                <span class="switcher-label">Recommended Events Based on Interests</span>
                            </label>
                        </div>
                        <div class="button-group">
                            <button type="button" class="btn save-btn">Save Preferences</button>
                            <button type="button" class="btn cancel-btn">Cancel</button>
                        </div>
                    </div>
                <?php endif; ?>

                <hr class="section-divider">

                <div class="card-body card-spacing">
                    <button type="button" class="btn help-btn">Help & Support</button>
                    <button type="button" class="btn delete-acc-btn" data-id="<?php echo $userId; ?>">Delete Account</button>
                </div>
            </div>
        </div>
    </div>
    <!--back to top button-->
    <a href="#" id="back-to-top" title="Back to Top">&uarr;</a>
    <script src="../js/script.js"></script>
    <!-- Footer Section -->
    <?php include('footer.php'); ?>
    <script src="../js/profilescript.js"></script>
    <?php include('modal.php'); ?>
    <script src="../js/modal.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Function to Render Donut Charts for Both Roles
        function renderOverviewDonut(role, data) {
            const ctx = role === 'Student' ?
                document.getElementById('studentOverviewDonut').getContext('2d') :
                document.getElementById('organizerOverviewDonut').getContext('2d');

            // Show the relevant canvas
            document.getElementById(role === 'Student' ? 'studentOverviewDonut' : 'organizerOverviewDonut').style.display = 'block';

            // Chart Data and Labels
            const chartData = role === 'Student' ? [data.totalAttended, data.totalVolunteered, data.totalParticipated] : [data.totalEvents, data.total_volunteering_events];
            const labels = role === 'Student' ? ['Attended', 'Volunteered', 'Participated'] : ['Total Events', 'Volunteering Events'];

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        label: `${role} Overview`,
                        data: chartData,
                        backgroundColor: ['#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0'],
                        hoverOffset: 4
                    }]
                },
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: `${role} Overview`
                        }
                    }
                }
            });
        }

        // Function to render the Upcoming Events as a Donut Chart
        function renderUpcomingEventsChart(data) {
            const ctx = document.getElementById('upcomingEventsDonutChart').getContext('2d');

            // Initialize the Donut Chart for Upcoming Events
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Skills Sharing', 'Community Clean-Up', 'Charity Event'],
                    datasets: [{
                        label: 'Upcoming Events Count',
                        data: [
                            data.upcomingCounts['Skills Sharing Session'],
                            data.upcomingCounts['Community Clean-Up'],
                            data.upcomingCounts['Charity Event']
                        ],
                        backgroundColor: ['#faaca8', '#36a2eb', '#ffcd56'],
                    }]
                },
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'Upcoming Events Overview'
                        }
                    },
                    responsive: true,
                    cutout: '60%', // Adjust the size of the hole in the middle
                    hoverOffset: 4,
                }
            });
        }

        // Fetch Data and Initialize Charts
        fetch('<?php echo $user["role"] === "Student" ? "fetch_dashboard_student.php" : "fetch_dashboard_organizer.php"; ?>')
            .then(response => response.json())
            .then(data => {
                const userRole = '<?php echo $user["role"]; ?>';

                if (userRole === 'Student') {
                    renderOverviewDonut('Student', data);
                } else if (userRole === 'Organizer') {
                    renderOverviewDonut('Organizer', data);
                }

                renderUpcomingEventsChart(data);
            })
            .catch(error => console.error('Error fetching chart data:', error));
    </script>

</body>

</html>