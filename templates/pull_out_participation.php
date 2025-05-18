<?php
session_start();
include '../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $_DELETE);
    $event_id = $_GET['event_id'] ?? null;
    $userId = $_SESSION['user_id'];

    $sql = "DELETE FROM participants WHERE eventId = ? AND userId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $event_id, $userId);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Participation removed successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to remove participation.']);
    }
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

?>