<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../config/connection.php';
header('Content-Type: application/json');

// Ensure clean output
ob_clean();

// Log the incoming request for debugging
error_log('Received request: ' . file_get_contents('php://input'));

try {
    // Check for user session
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('User not logged in');
    }

    // Get and decode the POST data
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Log decoded data
    error_log('Decoded data: ' . print_r($data, true));

    // Validate input data
    if (!$data || !isset($data['alert']) || !isset($data['news']) || !isset($data['recommend'])) {
        throw new Exception('Invalid data received: ' . print_r($data, true));
    }

    // Sanitize and validate the data
    $userId = $_SESSION['user_id'];
    $alert = filter_var($data['alert'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 1]]);
    $news = filter_var($data['news'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 1]]);
    $recommend = filter_var($data['recommend'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 1]]);

    // Update the profile
    $stmt = $conn->prepare("UPDATE profiles SET alert = ?, news = ?, recommend = ? WHERE userId = ?");
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("iiii", $alert, $news, $recommend, $userId);

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    // Check if the update actually affected any rows
    if ($stmt->affected_rows === 0) {
        // If no rows were updated, let's check if the profile exists
        $checkStmt = $conn->prepare("SELECT COUNT(*) as count FROM profiles WHERE userId = ?");
        $checkStmt->bind_param("i", $userId);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['count'] === 0) {
            // Profile doesn't exist, create it
            $insertStmt = $conn->prepare("INSERT INTO profiles (userId, alert, news, recommend) VALUES (?, ?, ?, ?)");
            $insertStmt->bind_param("iiii", $userId, $alert, $news, $recommend);
            if (!$insertStmt->execute()) {
                throw new Exception("Insert failed: " . $insertStmt->error);
            }
        }
    }

    // Success response
    $response = [
        'success' => true,
        'message' => 'Preferences updated successfully'
    ];
    
    echo json_encode($response);
    exit;

} catch (Exception $e) {
    error_log("Error in update_preferences.php: " . $e->getMessage());
    
    $response = [
        'success' => false,
        'message' => 'Server error occurred: ' . $e->getMessage()
    ];
    
    echo json_encode($response);
    exit;
}
?>