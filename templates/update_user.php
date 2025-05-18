<?php
include '../config/connection.php';

header('Content-Type: application/json'); // Set the response type to JSON

$response = ['success' => false, 'error' => '']; // Default response

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Parse JSON input
        $data = json_decode(file_get_contents('php://input'), true);

        // Debugging: Log the received data
        error_log('Data received: ' . print_r($data, true));

        // Extract data fields
        $id = $data['id'] ?? null;
        $fullName = $data['full_name'] ?? null;
        $email = $data['email'] ?? null;
        $role = $data['role'] ?? null;
        $contactNumber = $data['contact_number'] ?? null;
        $dateOfBirth = $data['date_of_birth'] ?? null;
        $gender = $data['gender'] ?? null;

        // Validate required fields
        if (!$id || !$fullName || !$email || !$role || !$contactNumber || !$dateOfBirth || !$gender) {
            throw new Exception('Missing required fields.');
        }

        // Prepare SQL query
        $query = "UPDATE users 
                  SET full_name = ?, email = ?, role = ?, contact_number = ?, date_of_birth = ?, gender = ? 
                  WHERE id = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            throw new Exception('Database prepare error: ' . $conn->error);
        }

        // Bind parameters and execute
        $stmt->bind_param('ssssssi', $fullName, $email, $role, $contactNumber, $dateOfBirth, $gender, $id);

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
