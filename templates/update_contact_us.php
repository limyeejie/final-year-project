<?php
include '../config/connection.php';

header('Content-Type: application/json'); // Set response type to JSON

$response = ['success' => false, 'error' => '']; // Default response

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Parse JSON input
        $data = json_decode(file_get_contents('php://input'), true);

        // Debugging: Log the received data
        error_log('Data received: ' . print_r($data, true));

        // Extract data fields
        $id = $data['id'] ?? null;
        $fullname = $data['fullname'] ?? null;
        $email = $data['email'] ?? null;
        $subject = $data['subject'] ?? null;
        $reason = $data['reason'] ?? null;
        $role = $data['role'] ?? null;
        $message = $data['message'] ?? null;

        // Prepare SQL query
        $query = "UPDATE contact_us 
                  SET fullname = ?, email = ?, subject = ?, reason = ?, role = ?, message = ? 
                  WHERE id = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            throw new Exception('Database prepare error: ' . $conn->error);
        }

        // Bind parameters and execute
        $stmt->bind_param('ssssssi', $fullname, $email, $subject, $reason, $role, $message, $id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows === 0) {
                // No rows were updated, but no error occurred; likely same data
                $response['success'] = true;
                $response['message'] = 'No changes detected, but query executed successfully.';
            } else {
                $response['success'] = true; // Update successful
                $response['message'] = 'Contact updated successfully.';
            }
        } else {
            throw new Exception('Failed to execute query: ' . $stmt->error);
        }

        $stmt->close();
    } else {
        throw new Exception('Invalid request method.');
    }
} catch (Exception $e) {
    $response['error'] = $e->getMessage(); // Capture and return errors
}

// Send JSON response
echo json_encode($response);
?>
