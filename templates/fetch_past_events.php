<?php
session_start();
include '../config/connection.php';

$userId = $_SESSION['user_id'];

// Get the current date
$currentDate = date('Y-m-d');

// Prepare and execute the query to fetch past events
$query = "
SELECT 
    e.id AS id, 
    e.title AS title, 
    e.date AS date, 
    e.location AS location, 
    e.description AS description, 
    e.image AS image,
    'event' AS type
FROM 
    events e
JOIN 
    participants p ON e.id = p.eventId
WHERE 
    e.date < ? AND p.userId = ? AND p.attendance = 1
ORDER BY date DESC
";
$stmt = $conn->prepare($query);
$stmt->bind_param('si', $currentDate, $userId);
$stmt->execute();
$result = $stmt->get_result();

$events = [];
while ($row = $result->fetch_assoc()) {
    $row['image'] = base64_encode($row['image']);
    $events[] = $row; // Add each event to the array
}

// Return events as JSON
$response = [
    'events' => $events
];

header('Content-Type: application/json');
echo json_encode($response); // Output the past events as JSON
?>
