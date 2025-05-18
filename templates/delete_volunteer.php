<?php
include '../config/connection.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);


if(isset($data['id'])) {
    $volunteerId = $data['id'];

    $stmt = $conn->prepare("DELETE FROM volunteers WHERE id = ?");
    $stmt->bind_param('i', $volunteerId);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Event deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting event.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error deleting event.']);
}
