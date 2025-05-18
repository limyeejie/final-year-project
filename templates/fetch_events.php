<?php
include '../config/connection.php';


$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'title';


$validSortColumns = ['title', 'date', 'location'];
if (!in_array($sortBy, $validSortColumns)) {
    $sortBy = 'title';  
}


$resultsPerPage = isset($_GET['results']) ? (int)$_GET['results'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $resultsPerPage;
$search = isset($_GET['search']) ? $_GET['search'] : '';

$currentDate = date('Y-m-d');


$query = "SELECT * FROM events WHERE title LIKE ? OR description LIKE ? ORDER BY $sortBy ASC LIMIT ?, ?";
$stmt = $conn->prepare($query);
$searchTerm = "{$search}%"; // Search pattern
$stmt->bind_param('ssii', $searchTerm, $searchTerm, $offset, $resultsPerPage);
$stmt->execute();
$result = $stmt->get_result();

$events = [];
while ($row = $result->fetch_assoc()) {
    $row['image'] = base64_encode($row['image']);  
    $events[] = $row; 
}


$totalEvents = $conn->query("SELECT COUNT(*) AS count FROM events WHERE title LIKE '$searchTerm' OR description LIKE '$searchTerm'")->fetch_assoc()['count'];
$totalPages = ceil($totalEvents / $resultsPerPage);


$response = [
    'events' => $events,
    'totalPages' => $totalPages
];

header('Content-Type: application/json');
echo json_encode($response); 
?>
