<?php
session_start();
require_once '../config/connection.php';

// Set JSON content type header
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Please log in to view recommendations"]);
    exit();
}

$userId = $_SESSION['user_id'];

try {
    // Get user's preferred categories from past participation
    $categoryQuery = "
        SELECT DISTINCT e.event_category, COUNT(*) as participation_count
        FROM participants p
        JOIN events e ON p.eventId = e.id
        WHERE p.userId = ?
        GROUP BY e.event_category
        ORDER BY participation_count DESC
    ";

    $stmt = $conn->prepare($categoryQuery);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $categoryResult = $stmt->get_result();

    $userCategories = [];
    while ($row = $categoryResult->fetch_assoc()) {
        $userCategories[] = $row['event_category'];
    }

    // If user has no history, include default categories
    if (empty($userCategories)) {
        $userCategories = ['Community Clean-Up', 'Skills Sharing Session', 'Charity Event'];
    }

    // Get recommended upcoming events
    $recommendQuery = "
        SELECT 
            e.id as eventId,
            e.title,
            e.date,
            e.location,
            CASE 
                WHEN e.event_category = 'Community Clean-Up' THEN 'volunteering'
                ELSE 'attending'
            END as linkType
        FROM events e
        WHERE e.date > CURRENT_TIMESTAMP
        AND e.id NOT IN (SELECT eventId FROM participants WHERE userId = ?)
        AND (e.due_registration_date IS NULL OR e.due_registration_date >= CURRENT_DATE)
        ORDER BY FIELD(e.event_category, " . str_repeat('?,', count($userCategories) - 1) . "?) DESC, e.date ASC
        LIMIT 4
    ";

    $stmt = $conn->prepare($recommendQuery);
    
    // Create parameter types string and array
    $types = 'i' . str_repeat('s', count($userCategories));
    $params = array_merge([$userId], $userCategories);
    
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $recommendations = [];
    while ($row = $result->fetch_assoc()) {
        $recommendations[] = $row;
    }

    echo json_encode($recommendations);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to load recommendations"]);
    error_log($e->getMessage());
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}
?>