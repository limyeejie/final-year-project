<?php
session_start();
include '../config/connection.php';

// Get the current date
$currentDate = date('Y-m-d');

$userId = $_SESSION['user_id']; 


$query =  "
SELECT 
    e.id AS id, 
    e.title AS title, 
    e.date AS date, 
    e.event_time as event_time,
    e.location AS location, 
    e.description AS description, 
    e.image AS image,
    e.organizer_name as organizer_name,
    e.organizer_number as organizer_number,
    e.organizer_contact as organizer_contact,
    e.max_participants as max_participants,
    'Event' AS type
FROM 
    events e
JOIN 
    participants p ON e.id = p.eventId
WHERE 
    e.date > ? AND p.userId = ? AND p.attendance = 0

UNION ALL

SELECT 
    v.id AS id, 
    v.title AS title, 
    v.date AS date, 
    v.event_time as event_time,
    v.location AS location, 
    v.description AS description, 
    v.image AS image,
    v.organizer_name as organizer_name,
    v.organizer_number as organizer_number,
    v.organizer_contact as organizer_contact,
    v.max_volunteer as max_participants,
    'Volunteer' AS type
FROM 
    volunteers v
JOIN 
    application_volunteer av ON v.id = av.volunteerId
WHERE 
    v.date > ? AND av.userId = ? AND av.attendance = 0

ORDER BY date DESC
"; 
$stmt = $conn->prepare($query);
$stmt->bind_param('sisi', $currentDate, $userId, $currentDate, $userId);
$stmt->execute();
$result = $stmt->get_result();

$events = [];
while ($row = $result->fetch_assoc()) {
    $row['image'] = base64_encode($row['image']);
    $events[] = $row; 
}


$response = [
    'events' => $events
];

header('Content-Type: application/json');
echo json_encode($response); 
?>
