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
        $organizer_contact = $data['organizer_contact'] ?? null;
        $date = $data['date'] ?? null;
        $startRegDate = $data['start_registration_date'] ?? null;
        $dueRegDate = $data['due_registration_date'] ?? null;
        $location = $data['location'] ?? null;
        $description = $data['description'] ?? null;
        $eventCategory = $data['event_category'] ?? null;
        $maxParticipants = isset($data['max_participants']) ? (int)$data['max_participants'] : 0;
        $eventTime = $data['event_time'] ?? null;
        $organizerName = $data['organizer_name'] ?? null;
        $organizerNumber = $data['organizer_number'] ?? null;

        // Validate required fields
        if (!$id || !$title || !$organizer_contact || !$date || !$startRegDate || !$dueRegDate || !$location || !$description || !$eventCategory ||
        !$maxParticipants || !$eventTime || !$organizerName || !$organizerNumber) {
            throw new Exception('Missing required fields.');
        }

        // Prepare SQL query
        $query = "UPDATE events 
                  SET title = ?, organizer_contact = ?, date = ?, start_registration_date = ?, due_registration_date = ?, 
                      location = ?, description = ?, event_category = ?, max_participants = ?,
                      event_time = ?, organizer_name = ?, organizer_number = ?
                  WHERE id = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            throw new Exception('Database prepare error: ' . $conn->error);
        }

        // Bind parameters and execute
        $stmt->bind_param('ssssssssssssi', 
            $title, $organizer_contact, $date, $startRegDate, $dueRegDate, 
            $location, $description, $eventCategory, $maxParticipants,
            $eventTime, $organizerName, $organizerNumber, 
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
?>
