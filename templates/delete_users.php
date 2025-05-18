<?php
include '../config/connection.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    $userId = $data['id'];


    $stmt = $conn->prepare("DELETE FROM notifications WHERE user_id = ?");
    $stmt->bind_param('i', $userId);
    if (!$stmt->execute()) {
        echo json_encode(['error' => 'Failed to delete related notifications']);
        exit;
    }
    
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param('i', $userId);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'User deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting user.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'User ID not provided.']);
}
?>
