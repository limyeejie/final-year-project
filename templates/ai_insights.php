<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Make sure the content type is JSON
header('Content-Type: application/json');

try {
    // Database connection
    include '../config/connection.php';

    // Initialize an empty response array
    $response = [
        'popularEvents' => [],
        'predictedVolunteers' => 0,  
        'predictedEvents' => []      
    ];

    // Query for popular events
    $popularEventsQuery = "
        SELECT event_category AS category, COUNT(*) AS count 
        FROM events 
        GROUP BY event_category 
        ORDER BY count DESC 
        LIMIT 5";
    $popularEventsResult = $conn->query($popularEventsQuery);
    if ($popularEventsResult) {
        while ($row = $popularEventsResult->fetch_assoc()) {
            $response['popularEvents'][] = [
                'category' => $row['category'],
                'count' => (int)$row['count']
            ];
        }
    }

    // Query for volunteer trends over time (monthly count of new volunteers)
    $volunteerTrendsQuery = "
        SELECT DATE_FORMAT(joined_at, '%Y-%m') AS month, COUNT(*) AS new_volunteers
        FROM application_volunteer
        GROUP BY month
        ORDER BY month ASC";
    $volunteerTrendsResult = $conn->query($volunteerTrendsQuery);
    $volunteerTrendNumbers = []; // Store monthly volunteer counts
    if ($volunteerTrendsResult) {
        while ($row = $volunteerTrendsResult->fetch_assoc()) {
            $response['volunteerTrends'][] = [
                'month' => $row['month'],
                'new_volunteers' => (int)$row['new_volunteers']
            ];
            $volunteerTrendNumbers[] = (int)$row['new_volunteers'];
        }
    }

    // --- Predict future volunteers and events using Python Script ---
    if (!empty($volunteerTrendNumbers)) {
        // Prepare the data to be passed to the Python script (as JSON)
        $trendData = json_encode(array_map(function($volunteerCount) {
            return ['new_volunteers' => $volunteerCount];
        }, $volunteerTrendNumbers));

        // Call the Python script to predict the next month's volunteer count and events
        $pythonScript = '../python/predict_volunteers.py'; 
        $command = escapeshellcmd("python3 $pythonScript '$trendData'");
        $output = shell_exec($command);

        // Check if output is empty or invalid
        if ($output === null || trim($output) === '') {
            // Handle the case where the Python script returned no output
            $response['predictedVolunteers'] = 1; 
            $response['predictedEvents'] = []; 
        } else {
            // Decode the result from the Python script 
            $prediction = json_decode($output, true);

            // Check if the prediction is valid
            if (json_last_error() === JSON_ERROR_NONE) {
                if (isset($prediction['predicted_volunteers'])) {
                    $response['predictedVolunteers'] = $prediction['predicted_volunteers'];
                }
                if (isset($prediction['predicted_events'])) {
                    $response['predictedEvents'] = $prediction['predicted_events'];
                } else {
                    $response['predictedEvents'] = []; 
                }
            } else {
                // Handle invalid JSON output
                $response['predictedVolunteers'] = 1; 
                $response['predictedEvents'] = []; 
            }
        }
    } else {
        $response['predictedVolunteers'] = 1; 
        $response['predictedEvents'] = []; 
    }

    // Query for event categories count
    $eventCategoryQuery = "
        SELECT event_category, COUNT(*) AS count
        FROM events
        GROUP BY event_category
        ORDER BY count DESC";
    
    $eventCategoryResult = $conn->query($eventCategoryQuery);

    $categoryData = [];
    $totalEvents = 0;
    if ($eventCategoryResult) {
        while ($row = $eventCategoryResult->fetch_assoc()) {
            $categoryData[] = [
                'category' => $row['event_category'],
                'count' => (int)$row['count']
            ];
            $totalEvents += (int)$row['count'];
        }
    }

    // Calculate probabilities for popular events
    if ($totalEvents > 0) {
        foreach ($categoryData as &$event) {
            $event['probability'] = round($event['count'] / $totalEvents, 2); 
        }

        // Predict popular events based on highest probability
        usort($categoryData, fn($a, $b) => $b['probability'] <=> $a['probability']);
        $response['popularEvents'] = $categoryData;

        // Send category data to Python for further refinement or prediction
        $categoryDataJson = json_encode($categoryData);

        // Call Python script to refine or predict the most popular event
        $pythonScript = '../python/predict_events.py'; 
        $command = escapeshellcmd("python3 $pythonScript '$categoryDataJson'");
        $output = shell_exec($command);

        // Check for valid output and return predicted events
        if ($output === null || trim($output) === '') {
            $response['predictedEvents'] = $categoryData[0]; 
        } else {
            // Parse Python script output for predicted events
            $prediction = json_decode($output, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($prediction['predicted_events'])) {
                $response['predictedEvents'] = $prediction['predicted_events'];
            } else {
                $response['predictedEvents'] = $categoryData[0]; 
            }
        }
    }

    // --- Return the response as JSON ---
    echo json_encode($response);

} catch (Exception $e) {
    // Catch any exceptions and return them as a JSON error
    echo json_encode(['error' => $e->getMessage()]);
}

?>



