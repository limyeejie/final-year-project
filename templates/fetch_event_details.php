<?php
include '../config/connection.php';

if (isset($_GET['eventId'])) {
    $eventId = intval($_GET['eventId']); // Sanitize the input

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $eventId); // Bind the event ID

    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the event was found
    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
        // Return the event details as JSON
        echo json_encode(['event' => $event]);
    } else {
        echo json_encode(['error' => 'Event not found.']);
    }

    // Close the statement and connection
    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid request.']);
}

// Close the database connection
$conn->close();
?>