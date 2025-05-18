<?php
session_start();
include '../config/connection.php';

header("Content-Type: application/json");

try {
    
    if (!isset($_SESSION['user_id'])) {
        throw new Exception("User is not logged in. Please log in to view recommendations.");
    }

    $user_id = $_SESSION['user_id'];
    $events = [];

    
    $checkInteractionsQuery = "
        SELECT COUNT(*) FROM user_interactions WHERE user_id = ?
    ";
    $stmt = $conn->prepare($checkInteractionsQuery);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($interactionCount);
    $stmt->fetch();
    $stmt->close();

    
    $contentBasedEvents = [];
    $collaborativeEvents = [];

    if ($interactionCount == 0) {
        $popularEventsSQL = "
            SELECT e.id, e.title, e.date, e.location, e.description, e.event_category
            FROM events e
            ORDER BY e.date DESC
            LIMIT 3
        ";

        $stmt = $conn->prepare($popularEventsSQL);
        $stmt->execute();
        $popularResult = $stmt->get_result();
        
        while ($row = $popularResult->fetch_assoc()) {
            $events[] = $row; 
        }
    } else {
        $categoryQuery = "
            SELECT e.event_category
            FROM user_interactions ui
            JOIN events e ON ui.event_id = e.id
            WHERE ui.user_id = ? 
            GROUP BY e.event_category 
            ORDER BY COUNT(*) DESC 
            LIMIT 1
        ";

        $stmt = $conn->prepare($categoryQuery);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($preferred_category);
        $stmt->fetch();
        $stmt->close();

        if (!empty($preferred_category)) {
            $contentBasedSQL = "
                SELECT e.id, e.title, e.date, e.location, e.description, e.event_category
                FROM events e
                LEFT JOIN participants p ON e.id = p.eventId AND p.userId = ? 
                WHERE e.event_category = ? AND p.eventId IS NULL
                ORDER BY RAND()
                LIMIT 3
            ";

            $stmt = $conn->prepare($contentBasedSQL);
            $stmt->bind_param("is", $user_id, $preferred_category);
            $stmt->execute();
            $contentBasedResult = $stmt->get_result();
            
            while ($row = $contentBasedResult->fetch_assoc()) {
                $contentBasedEvents[] = $row; 
            }
        }

        $collaborativeSQL = "
            SELECT e.id, e.title, e.date, e.location, e.description, e.event_category
            FROM events e
            LEFT JOIN participants p ON e.id = p.eventId AND p.userId != ? 
            WHERE p.userId IN (
                SELECT ui.user_id
                FROM user_interactions ui
                WHERE ui.event_id IN (
                    SELECT event_id
                    FROM user_interactions
                    WHERE user_id = ?
                )
            )
            AND e.id NOT IN (
                SELECT event_id FROM user_interactions WHERE user_id = ?
            )
            LIMIT 3
        ";

        $stmt = $conn->prepare($collaborativeSQL);
        $stmt->bind_param("iii", $user_id, $user_id, $user_id);
        $stmt->execute();
        $collaborativeResult = $stmt->get_result();
        
        while ($row = $collaborativeResult->fetch_assoc()) {
            $collaborativeEvents[] = $row; 
        }

        $mergedEvents = array_merge($contentBasedEvents, $collaborativeEvents);

        // Remove duplicates based on event ID
        $uniqueEvents = [];
        foreach ($mergedEvents as $event) {
            $uniqueEvents[$event['id']] = $event;
        }

        // Get up to 3 unique events
        $uniqueEvents = array_values($uniqueEvents); // Re-index array
        $events = array_slice($uniqueEvents, 0, 3);

    }

    echo json_encode([
        "status" => "success",
        "data" => $events
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?>