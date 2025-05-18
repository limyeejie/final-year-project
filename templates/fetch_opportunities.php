<?php
include '../config/connection.php';

// Check connection and set proper charset
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set proper charset for the connection
$conn->set_charset('utf8mb4');  // Using utf8mb4 instead of utf8

// Set proper charset in PHP
header('Content-Type: application/json; charset=utf8mb4');

// Ensure PHP's internal encoding is set correctly
mb_internal_encoding('UTF-8');

// Fetch data with error handling
$sql = "SELECT * FROM volunteers";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

$opportunities = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Clean and encode each field if necessary
        array_walk_recursive($row, function(&$item) {
            if (is_string($item)) {
                // Remove invalid UTF-8 sequences
                $item = mb_convert_encoding($item, 'UTF-8', 'UTF-8');
            }
        });
        $opportunities[] = $row;
    }
    echo "Number of rows fetched: " . count($opportunities) . "\n";
} else {
    echo "No data found";
    exit;
}

// JSON encode with error checking and proper options
$jsonOutput = json_encode($opportunities, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

if ($jsonOutput === false) {
    echo "JSON encoding error: " . json_last_error_msg() . "\n";
    // Additional debug information
    foreach ($opportunities as $key => $row) {
        array_walk_recursive($row, function($item, $key) {
            if (is_string($item)) {
                echo "Checking field '$key': " . bin2hex($item) . "\n";
            }
        });
    }
} else {
    echo $jsonOutput;
}

$conn->close();
?>