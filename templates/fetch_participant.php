<?php

include '../config/connection.php';
header('Content-Type: application/json');


$eventId = isset($_GET['eventId']) ? intval($_GET['eventId']) :'0';



$sql = "SELECT eventId, userId, full_name, email, contact_number, joined_at, attendance FROM participants";
if ($eventId > 0) {
    $sql .= " WHERE eventId = ?";
} 

$stmt = $conn->prepare($sql);
if ($eventId > 0) {
    $stmt->bind_param("i", $eventId);
}

$stmt->execute();
$result = $stmt->get_result();

$participants = [];
while ($row = $result->fetch_assoc()) {
    $participants[] = $row;
}

echo json_encode(["participants" => $participants]);

$stmt->close();
$conn->close();

?>