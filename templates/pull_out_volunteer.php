<?php
session_start();
include '../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $_DELETE);
    $volunteer_id = $_GET['volunteer_id'] ?? null;
    $userId = $_SESSION['user_id'];

    $sql = "DELETE FROM application_volunteer WHERE volunteerId = ? AND userId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $volunteer_id, $userId);

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