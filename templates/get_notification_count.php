<?php 
session_start();
include '../config/connection.php';
$user_id = $_SESSION['user_id'];


$sql = "SELECT COUNT(*) AS unread_count FROM notifications WHERE user_id = ? AND read_status = 0";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($unreadCount);

    if ($stmt->fetch()) {
        echo json_encode(['count' => $unreadCount]); 
    } else {
        echo json_encode(['count' => 0, 'error' => 'No notifications found.']);
    }
    $stmt->close();
} else {
    echo json_encode(['count' => 0, 'error' => 'Could not fetch notification count.']);
}

$conn->close();
?>