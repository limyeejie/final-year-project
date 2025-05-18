<?php

session_start();
include '../config/connection.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'User not logged in'
    ]);
    exit;
}

$userId = $_SESSION['user_id'];

try {
    $sql = "SELECT * FROM profiles WHERE userId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $profile = $result->fetch_assoc();

    echo json_encode([
        "success" => true,
        "profile" => $profile
    ]);

} catch (Exception $e) {
    error_log("Error in fetch_preferences.php: " . $e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => "Error fetching preferences",
        "debug" => $e->getMessage()
    ]);
}

$stmt->close();
$conn->close();

?>
