<?php
session_start();

include '../config/connection.php';

$userId = $_SESSION['user_id'];


$currentDate = date('Y-m-d');


$summaryQuery = "
    SELECT
        (
            SELECT COUNT(*) 
            FROM events
        ) AS total_events,
        (
            SELECT COUNT(*) 
            FROM volunteers
        ) AS total_volunteering_events,
        (
            SELECT COUNT(*) 
            FROM participants 
            WHERE attendance = 1
        ) AS total_participated,
        (
            SELECT COUNT(*) 
            FROM application_volunteer 
            WHERE attendance = 1
        ) AS total_volunteered
";

$stmt = $conn->prepare($summaryQuery);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$totalEvents = $row['total_events'];
$totalVolunteeringEvents = $row['total_volunteering_events'];
$totalParticipated = $row['total_participated'];
$totalVolunteered = $row['total_volunteered'];

$categories = ['Skills Sharing Session', 'Community Clean-Up', 'Charity Event'];
$upcomingCounts = [];

foreach ($categories as $category) {
    $upcomingQuery =  "SELECT COUNT(*) AS count FROM events WHERE event_category = ? AND date >= ?";
    $stmt = $conn->prepare($upcomingQuery);
    $stmt->bind_param('ss', $category, $currentDate);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['count'];
    $upcomingCounts[$category] = $count;
}

$totalUpcomingQuery = "SELECT COUNT(*) AS total_upcoming FROM events WHERE date >= ?";
$stmt = $conn->prepare($totalUpcomingQuery);
$stmt->bind_param('s', $currentDate);
$stmt->execute();
$totalUpcomingResult = $stmt->get_result();
$totalUpcoming = $totalUpcomingResult->fetch_assoc()['total_upcoming'];

$response = [
    'totalEvents' => $totalEvents,
    'total_volunteering_events' => $totalVolunteeringEvents,
    'totalParticipated' => $totalParticipated,
    'totalVolunteered' => $totalVolunteered,
    'upcomingCounts' => $upcomingCounts,
    'totalUpcoming' => $totalUpcoming,
];
header('Content-Type: application/json');
echo json_encode($response);
?>