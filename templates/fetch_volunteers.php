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


$query = "SELECT * FROM volunteers WHERE title LIKE ? OR description LIKE ? ORDER BY $sortBy ASC LIMIT ?, ?";
$stmt = $conn->prepare($query);
$searchTerm = "{$search}%"; // Search pattern
$stmt->bind_param('ssii', $searchTerm, $searchTerm, $offset, $resultsPerPage);
$stmt->execute();
$result = $stmt->get_result();

$volunteers = [];
while ($row = $result->fetch_assoc()) {
    $row['image'] = base64_encode($row['image']);
    $volunteers[] = $row; 
}


$countQuery = "SELECT COUNT(*) AS count FROM volunteers WHERE title LIKE ? OR description LIKE ?";
$countStmt = $conn->prepare($countQuery);
$countStmt->bind_param('ss', $searchTerm, $searchTerm);
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalEvents = $countResult->fetch_assoc()['count'];
$totalPages = ceil($totalEvents / $resultsPerPage);

// Close the prepared statements to free resources
$stmt->close();
$countStmt->close();

// Return volunteers and total pages as JSON
$response = [
    'volunteers' => $volunteers,
    'totalPages' => $totalPages
];

header('Content-Type: application/json');
echo json_encode($response); 



?>
