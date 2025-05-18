<?php
include '../config/connection.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['attendance'])) {
    echo json_encode(["success" => false, "message" => "Invalid data."]);
    exit;
}

$attendanceData = $data['attendance'];

$stmt = $conn->prepare("UPDATE participants SET attendance = ? WHERE userId = ?");
foreach ($attendanceData as $entry) {
    $attendance = intval($entry['attendance']);
    $userId = intval($entry['userId']);

    $stmt->bind_param("ii", $attendance, $userId);
    $stmt->execute();
}

$stmt->close();
$conn->close();

echo json_encode(["success" => true]);
?>