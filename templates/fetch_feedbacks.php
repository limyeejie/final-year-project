<?php
include '../config/connection.php';

header('Content-Type: application/json');

$eventsQuery = "
    SELECT id, title, date, location 
    FROM events 
    WHERE date < CURDATE()
";

$eventsResult = $conn->query($eventsQuery);

if ($eventsResult) {
    $events = [];

    while ($event = $eventsResult->fetch_assoc()) {
        $eventId = $event['id'];

        $feedbackQuery = "SELECT rating, comment FROM feedback WHERE event_id = ?";

        $stmt = $conn->prepare($feedbackQuery);
        $stmt->bind_param("i", $eventId);
        $stmt->execute();
        $feedbackResult = $stmt->get_result();

        $feedbacks = [];

        while ($feedback = $feedbackResult->fetch_assoc()) {
            $feedbacks[] = $feedback;
        }

        $event['feedbacks'] = $feedbacks;
        $events[] = $event;
    }

    echo json_encode([
        'success' => true,
        'events' => $events
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch feedbacks.'
    ]);
}

$conn->close();
?>