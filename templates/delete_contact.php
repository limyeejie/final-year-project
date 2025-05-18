<?php
include '../config/connection.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    $contactId = $data['id'];

    $stmt = $conn->prepare("DELETE FROM contact_us WHERE id = ?");
    $stmt->bind_param('i', $contactId);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Contact inquiry deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting contact inquiry.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Contact ID not provided.']);
}
?>
