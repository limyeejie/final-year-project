<?php
header('Content-Type: application/json');

// Database connection details
$host = 'localhost';
$db = 'eventmanagementsystem';
$user = 'root';
$pass = '';

// Establish a database connection
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed']));
}

// Retrieve the search query, page, and results per page parameters
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;  
$resultsPerPage = isset($_GET['results']) ? (int)$_GET['results'] : 5;

// Calculate the OFFSET and LIMIT for the SQL query
$offset = ($page - 1) * $resultsPerPage;

// Build the SQL query with optional search condition
$sql = "SELECT `id`, `fullname`, `email`, `subject`, `reason`, `role`, `message` FROM `contact_us`";

if ($search) {
    $sql .= " WHERE `fullname` LIKE '%$search%' 
              OR `email` LIKE '%$search%' 
              OR `subject` LIKE '%$search%' 
              OR `message` LIKE '%$search%'";
}

// Add LIMIT and OFFSET for pagination
$sql .= " LIMIT $resultsPerPage OFFSET $offset";

// Execute the query and fetch the result
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Query to count the total number of records for pagination
$countSql = "SELECT COUNT(*) as total FROM `contact_us`";

if ($search) {
    $countSql .= " WHERE `fullname` LIKE '%$search%' 
                   OR `email` LIKE '%$search%' 
                   OR `subject` LIKE '%$search%' 
                   OR `message` LIKE '%$search%'";
}

$countResult = $conn->query($countSql);
$totalRecords = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $resultsPerPage);

// Output JSON response with contact data and total pages
echo json_encode([
    'contacts' => $data,
    'totalPages' => $totalPages
]);

$conn->close();
?>
