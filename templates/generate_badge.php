<?php
session_start();
include '../config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  die("User not logged in.");
}

// Fetch the user's name from the session
$userId = $_SESSION['user_id'];

// Fetch user's name
$stmt = $conn->prepare("SELECT full_name FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
  die("User not found.");
}
$user = $result->fetch_assoc();
$name = $user['full_name'];

// Get badge ID from the URL
$badgeId = isset($_GET['badge_id']) ? $_GET['badge_id'] : null;
if ($badgeId === null) {
  die("Badge ID not provided.");
}

// Determine badge image and congratulatory text based on badge ID
switch ($badgeId) {
  case 'first-event':
    $badgeImage = '../images/profile/cert_bronze.png';
    break;
  case 'community-helper':
    $badgeImage = '../images/profile/cert_silver.png';
    break;
  case 'event-leader':
    $badgeImage = '../images/profile/cert_gold.png';
    break;
  default:
    die("Invalid badge ID: " . htmlspecialchars($badgeId));
}

// Set Content-Type header for image download
header('Content-Type: image/png');
echo file_get_contents($badgeImage);

// Read the image file content
$imageData = file_get_contents($badgeImage);

// Send the image data to the browser for download
echo $imageData;

$conn->close();
?>