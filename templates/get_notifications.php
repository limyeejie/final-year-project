<?php

include '../config/connection.php';

session_start();


$userId = $_SESSION['user_id'];

$sql = "SELECT message, time FROM notifications WHERE user_id = ? AND read_status = 0 ORDER BY time DESC";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $userId);

    $stmt->execute();
    $result = $stmt->get_result();

    $notifications = [];

    while ($row = $result->fetch_assoc()) {
        $notifications[] = [
            'message' => $row['message'],
            'time' => $row['time']
        ];
    }

    echo json_encode(['notifications' => $notifications]);

    $stmt ->close();

} else {
    echo json_encode(['error' => 'Failed to fetch notifications.']);
}
$conn->close();

?>