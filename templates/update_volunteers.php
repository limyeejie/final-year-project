<?php
include '../config/connection.php';

header('Content-Type: application/json'); // Ensure JSON response

$response = ['success' => false, 'error' => '']; // Default response

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Parse JSON input
        $data = json_decode(file_get_contents('php://input'), true);

        // Debugging: Log the received data
        error_log('Data received: ' . print_r($data, true));

        // Extract data fields
        $id = $data['id'] ?? null;
        $title = $data['title'] ?? null;
        $date = $data['date'] ?? null;
        $location = $data['location'] ?? null;
        $description = $data['description'] ?? null;
        $requirement = $data['requirement'] ?? null;
        $maxVolunteers = isset($data['max_volunteer']) ? (int)$data['max_volunteer'] : 0;
        $organizerContact = $data['organizer_contact'] ?? null;
        $eventTime = $data['event_time'] ?? null;
        $organizerName = $data['organizer_name'] ?? null;
        $organizerNumber = $data['organizer_number'] ?? null;

        if (!$id || !$title || !$date || !$location || !$description || !$requirement || !$maxVolunteers 
        || !$organizerContact || !$eventTime || !$organizerName || !$organizerNumber) {
            throw new Exception('Missing required fields.');
        }

        // Prepare SQL query
        $query = "UPDATE volunteers 
        SET 
            title = ?, 
            date = ?, 
            location = ?, 
            description = ?, 
            requirement = ?, 
            max_volunteer = ?, 
            organizer_contact = ?, 
            event_time = ?, 
            organizer_name = ?, 
            organizer_number = ? 
        WHERE id = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            throw new Exception('Database prepare error: ' . $conn->error);
        }

        // Bind parameters and execute
        $stmt->bind_param(
            'sssssissssi', // Corresponding data types
            $title,
            $date,
            $location,
            $description,
            $requirement,
            $maxVolunteers,
            $organizerContact,
            $eventTime,
            $organizerName,
            $organizerNumber,
            $id
        );

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $response['success'] = true; // Update successful
        } else {
            throw new Exception('No rows affected. Update failed.');
        }
    } else {
        throw new Exception('Invalid request method.');
    }
} catch (Exception $e) {
    $response['error'] = $e->getMessage(); // Capture and return errors
}

// Send JSON response
echo json_encode($response);
