<?php

include '../config/connection.php';
header('Content-Type: application/json');


$volunteerId = isset($_GET['volunteerId']) ? intval($_GET['volunteerId']) :'0';



$sql = "SELECT volunteerId, userId, full_name, email, contact_number, joined_at, attendance FROM application_volunteer";
if ($volunteerId > 0) {
    $sql .= " WHERE volunteerId = ?";
} 

$stmt = $conn->prepare($sql);
if ($volunteerId > 0) {
    $stmt->bind_param("i", $volunteerId);
}

$stmt->execute();
$result = $stmt->get_result();

$volunteers = [];
while ($row = $result->fetch_assoc()) {
    $volunteers[] = $row;
}

echo json_encode(["volunteers" => $volunteers]);

$stmt->close();
$conn->close();

?>