<?php
include '../config/connection.php';

function sendLowParticipationNotifications($conn) {
    $today = date('Y-m-d');
    // Calculate the target date
    $fiveDaysLater = date('Y-m-d', strtotime('+5 days'));

    // Query to find events with low participation
    $eventQuery = "
        SELECT 
            e.id AS event_id, 
            e.title AS event_title, 
            e.max_participants, 
            COUNT(p.userId) AS current_participants, 
            'event' AS type
        FROM 
            events e
        LEFT JOIN 
            participants p ON e.id = p.eventId
        WHERE 
            e.date BETWEEN ? AND ?
        GROUP BY 
            e.id, e.title, e.max_participants
        HAVING 
            (current_participants / e.max_participants) < 0.75
    ";

    $stmt = $conn->prepare($eventQuery);
    $stmt->bind_param('ss', $today, $fiveDaysLater);
    $stmt->execute();
    $result = $stmt->get_result();

    $notifications = [];
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }

    // If there are events with low participation, notify students
    if (!empty($notifications)) {
        $studentQuery = "SELECT id FROM users WHERE role = 'Student'";
        $studentResult = $conn->query($studentQuery);

        if ($studentResult->num_rows > 0) {
            while ($student = $studentResult->fetch_assoc()) {
                foreach ($notifications as $notification) {
                    $message = "The participation for " . $notification['type'] . " '" . $notification['event_title'] . "' is below 75%. Join Now!";
                    $insertNotification = "
                        INSERT INTO notifications (user_id, message, time) 
                        VALUES (?, ?, NOW())
                    ";
                    $stmt = $conn->prepare($insertNotification);
                    $stmt->bind_param('is', $student['id'], $message);
                    $stmt->execute();
                }
            }
        }
    }

    return ['status' => 'Notifications sent successfully.'];
}

?>