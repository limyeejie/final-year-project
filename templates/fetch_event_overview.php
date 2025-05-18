<?php

include '../config/connection.php';

$currentDate = date('Y-m-d');

$query = "
SELECT 
    e.id AS id, 
    e.title AS title, 
    e.date AS date, 
    e.location AS location, 
    e.description AS description, 
    e.image AS image, 
    e.organizer_contact AS organizer_contact, 
    COUNT(p.userId) AS count,
    e.max_participants AS max_participants,
    'Event' AS type
FROM 
    events e
LEFT JOIN 
    participants p ON e.id = p.eventId
GROUP BY 
    e.id
HAVING 
    e.date > ?

UNION ALL

SELECT 
    v.id AS id, 
    v.title AS title, 
    v.date AS date, 
    v.location AS location, 
    v.description AS description, 
    v.image AS image, 
    v.organizer_contact AS organizer_contact, 
    COUNT(av.userId) AS count,
    v.max_volunteer AS max_participants,
    'Volunteer' AS type
FROM 
    volunteers v
LEFT JOIN 
    application_volunteer av ON v.id = av.volunteerId
GROUP BY 
    v.id
HAVING 
    v.date > ?

ORDER BY 
    date DESC
";

$stmt = $conn->prepare($query);

$stmt->bind_param("ss", $currentDate, $currentDate);
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