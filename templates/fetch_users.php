<?php
include '../config/connection.php';

$resultsPerPage = isset($_GET['results']) ? (int)$_GET['results'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $resultsPerPage;
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Modify query based on whether there is a search term
if ($search) {
    $query = "SELECT id, full_name, email, role, contact_number, date_of_birth, gender, created_at 
              FROM users 
              WHERE full_name LIKE ? OR email LIKE ? 
              LIMIT ?, ?";
    $stmt = $conn->prepare($query);
    $searchTerm = "%{$search}%";
    $stmt->bind_param('ssii', $searchTerm, $searchTerm, $offset, $resultsPerPage);
} else {
    $query = "SELECT id, full_name, email, role, contact_number, date_of_birth, gender, created_at 
              FROM users 
              LIMIT ?, ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $offset, $resultsPerPage);
}

$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row; // Add each user to the array without password field
}

// Fetch total count of users for pagination
if ($search) {
    $totalUsersQuery = "SELECT COUNT(*) AS count FROM users WHERE full_name LIKE '$searchTerm' OR email LIKE '$searchTerm'";
} else {
    $totalUsersQuery = "SELECT COUNT(*) AS count FROM users";
}

$totalUsers = $conn->query($totalUsersQuery)->fetch_assoc()['count'];
$totalPages = ceil($totalUsers / $resultsPerPage);

// Return users and total pages as JSON
$response = [
    'users' => $users,
    'totalPages' => $totalPages
];

header('Content-Type: application/json');
echo json_encode($response);
?>
