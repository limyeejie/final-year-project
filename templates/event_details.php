<?php

session_start();
// Include database connection
include '../config/connection.php';

// Get the event ID from the URL query parameter
$eventId = isset($_GET['eventId']) ? (int)$_GET['eventId'] : 1; // Default to 1 if no ID is provided

// Check if the user ID is set in the session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Check if the user exists in the users table
    $checkUserQuery = "SELECT * FROM users WHERE id = ?";
    $stmtCheckUser = $conn->prepare($checkUserQuery);
    $stmtCheckUser->bind_param('i', $user_id);
    $stmtCheckUser->execute();
    $resultCheckUser = $stmtCheckUser->get_result();

    if ($resultCheckUser->num_rows > 0) {
        // Log the "view" interaction in the user_interactions table
        $interaction_type = 'view';
        if ($eventId && $user_id) { // Ensure both user_id and event_id are set
            $logQuery = "INSERT INTO user_interactions (user_id, event_id, interaction_type) VALUES (?, ?, ?)";
            $stmtLog = $conn->prepare($logQuery);
            $stmtLog->bind_param("iis", $user_id, $eventId, $interaction_type);
            $stmtLog->execute();
            $stmtLog->close();
        }
    } else {
        // Handle the case where the user does not exist in the users table
        echo "User does not exist.";
        exit;
    }
    $stmtCheckUser->close();
} else {
    // Handle the case where the user ID is not set in the session
    echo "User ID is not set in the session.";
    exit;
}

// Prepare and execute the query to fetch event details
$query = "SELECT * FROM events WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $eventId);
$stmt->execute();
$result = $stmt->get_result();

// Check if an event was found
if ($result->num_rows > 0) {
    $event = $result->fetch_assoc();
} else {
    // Handle the case where the event does not exist
    $event = null;
}

// Close the statement
$stmt->close();

// Get the participant count for the event
$countQuery = "SELECT COUNT(*) as participant_count FROM participants WHERE eventId = ?";
$countStmt = $conn->prepare($countQuery);
$countStmt->bind_param('i', $eventId);
$countStmt->execute();
$countResult = $countStmt->get_result();
$participantCount = $countResult->fetch_assoc()['participant_count'];

// Close the statement
$countStmt->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details - Event <?php echo $eventId; ?></title>
    <link rel="stylesheet" href="../css/styles.css"> <!-- Common CSS -->
    <link rel="stylesheet" href="../css/events_details.css"> <!-- Custom CSS for event details -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/purecss@2.0.6/build/pure-min.css">
</head>
<body>

    <!-- Header Section -->
    <?php include('header.php'); ?>

    <!-- Event Details Section -->
    <div class="event-details-container">
        <div class="event-image-section">
            <div class="event-image">
                <?php if ($event && isset($event['image'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($event['image']); ?>" alt="Event Image">
                <?php else: ?>
                    <img src="../images/event_image_placeholder.jpg" alt="Event Image">
                <?php endif; ?>
            </div>
        </div>
        <div class="event-info-section">
            <?php if ($event): ?>
                <h1 class="event-title"><?php echo htmlspecialchars($event['title']); ?></h1>
                <p class="event-date-location">Date & Location: <?php echo htmlspecialchars($event['date']) . ' &bullet; ' . htmlspecialchars($event['location']); ?></p>
                <p class="event-description">Time:<?php echo htmlspecialchars($event['event_time']); ?> </p>
                <p class="event-description">Event Description: <?php echo htmlspecialchars($event['description']); ?></p>
                <p class="event-description">Registration Start Date: <?php echo htmlspecialchars($event['start_registration_date']); ?></p>
                <p class="event-description">Registration Due Date: <?php echo htmlspecialchars($event['due_registration_date']); ?></p>
                <p class="event-description">Max Participants: <?php echo htmlspecialchars($event['max_participants']); ?></p>
                <p class="event-description">Participants Joined: <?php echo htmlspecialchars($participantCount) . ' / ' . htmlspecialchars($event['max_participants']); ?>
                <p class="event-description">Organizer Name:<?php echo htmlspecialchars($event['organizer_name']); ?>  </p>
                <p class="event-organizer-contact">Organizer Contact (Email): <?php echo htmlspecialchars($event['organizer_contact'] ?? 'info@example.com'); ?></p>
                <p class="event-description">Organizer Contact (Phone):<?php echo htmlspecialchars($event['organizer_number']); ?>  </p>
                <div class="action-buttons">
                    <button class="go-back-btn" onclick="history.back()">Go Back</button>
                    <form action="join_event.php" method="POST" style="display: inline;">
                        <input type="hidden" name="eventId" value="<?php echo $eventId; ?>">
                        <input type="hidden" name="interaction_type" value="join">
                        <button type="submit" class="join-now-btn">Join Now</button>
                    </form>

                </div>
            <?php else: ?>
                <h1>Event Not Found</h1>
                <p>Sorry, the event you are looking for does not exist.</p>
                <button class="go-back-btn" onclick="history.back()">Go Back</button>
            <?php endif; ?>
        </div>
    </div>
    <!--back to top button-->
    <a href="#" id="back-to-top" title="Back to Top">&uarr;</a>
    <script src="../js/script.js"></script>
    <!-- Footer Section -->
    <?php include('footer.php'); ?>

</body>
</html>
