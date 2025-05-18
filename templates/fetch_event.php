<?php
include '../config/connection.php';

$resultsPerPage = isset($_GET['results']) ? (int)$_GET['results'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $resultsPerPage;
$search = isset($_GET['search']) ? $_GET['search'] : '';

$currentDate = date('Y-m-d');

// Prepare and execute the query to fetch events
$query = "SELECT * FROM events WHERE date >= ? AND (title LIKE ? OR description LIKE ?) LIMIT ?, ?";
$stmt = $conn->prepare($query);
$searchTerm = "{$search}%"; // Search pattern
$stmt->bind_param('sssii', $currentDate, $searchTerm, $searchTerm, $offset, $resultsPerPage);
$stmt->execute();
$result = $stmt->get_result();

$events = [];
while ($row = $result->fetch_assoc()) {
    $row['image'] = base64_encode($row['image']);
    $events[] = $row; // Add each event to the array
    
}

// Fetch total events count
$totalEvents = $conn->query("SELECT COUNT(*) AS count FROM events WHERE title LIKE '$searchTerm' OR description LIKE '$searchTerm'")->fetch_assoc()['count'];
$totalPages = ceil($totalEvents / $resultsPerPage);


// Return events and total pages as JSON
$response = [
    'events' => $events,
    'totalPages' => $totalPages
];

header('Content-Type: application/json');
echo json_encode($response); // Output the events and pagination info as JSON
?>

