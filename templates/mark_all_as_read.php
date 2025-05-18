<?php

session_start();
include '../config/connection.php';




if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if ($data['action'] === 'mark_all_as_read' && isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $sql = "UPDATE notifications SET read_status = 1 WHERE read_status = 0 AND user_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update notifications.']);
        }
        
        $stmt->close();

    } else {
        echo json_encode(['success' => false, 'message' => 'User not logged in or invalid request data.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
$conn->close();
?>