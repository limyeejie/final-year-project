<?php
header('Content-Type: application/json');

// Database connection
$conn = new mysqli("localhost", "root", "", "eventmanagementsystem");

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Query data based on role for users
$data = [
    "organisers" => $conn->query("SELECT COUNT(*) AS count FROM users WHERE role = 'Organizer'")->fetch_assoc()['count'],
    "students" => $conn->query("SELECT COUNT(*) AS count FROM users WHERE role = 'Student'")->fetch_assoc()['count'],
    "events" => $conn->query("SELECT COUNT(*) AS count FROM events")->fetch_assoc()['count'],
    "volunteers" => $conn->query("SELECT COUNT(*) AS count FROM volunteers")->fetch_assoc()['count'],
    "registeredParticipants" => $conn->query("SELECT COUNT(*) AS count FROM participants")->fetch_assoc()['count'],
    "registeredVolunteers" => $conn->query("SELECT COUNT(*) AS count FROM application_volunteer")->fetch_assoc()['count'],
];

// Return JSON data
echo json_encode($data);
$conn->close();
?>
