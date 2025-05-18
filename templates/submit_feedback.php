<?php
include '../config/connection.php';


$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $eventName = $data['event_name'];
    $eventId = $data['event_id'];
    $rating = $data['rating'];
    $comment = $data['comment'];


    $query = "INSERT INTO feedback (event_name, event_id, rating, comment) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('siis',$eventName, $eventId, $rating, $comment);
    if ($stmt->execute()) {
        $response = ['success' => true];

    } else {
        $response = ['success' => false];
    }
} else {
    $response = ['success' => false];
}

header('Content-Type: application/json');
echo json_encode($response);

?>