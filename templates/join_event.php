<?php
include 'modal.php';

session_start();
include '../config/connection.php';

$userId = $_SESSION['user_id'];
$eventId = isset($_POST['eventId']) ? (int)$_POST['eventId'] : 0;

if ($eventId > 0) {

    $checkQuery = "SELECT * FROM participants WHERE userId = ? AND eventId = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("ii", $userId, $eventId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        //echo "<script>alert('You have already registered in the event successfully!'); window.location.href = 'events.php'</script>";
        $redirectUrl = 'events.php';
        echo "<script>
            document.querySelector('#failureModal .modal-close-btn')
                .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
            showModal('failure', 'You have already registered in the event successfully!');
        </script>";
    } else {
        $eventQuery = 'SELECT max_participants, start_registration_date, due_registration_date FROM events WHERE id = ?';
        $eventStmt = $conn->prepare($eventQuery);
        $eventStmt->bind_param("i", $eventId);
        $eventStmt->execute();
        $eventResult = $eventStmt->get_result();

        if ($eventResult->num_rows > 0) {
            $event = $eventResult->fetch_assoc();
            $maxParticipants = $event['max_participants'];
            $registrationStartDate = $event['start_registration_date'];
            $registrationDueDate = $event['due_registration_date'];
            $currentDate = date("Y-m-d");

            if ($currentDate >= $registrationStartDate && $currentDate <= $registrationDueDate) {
                $countQuery = "SELECT COUNT(*) as current_participants FROM participants WHERE eventId = ?";
                $countStmt = $conn->prepare($countQuery);
                $countStmt->bind_param("i", $eventId);
                $countStmt->execute();
                $countResult = $countStmt->get_result();
                $countData = $countResult->fetch_assoc();
                $currentParticipants = $countData['current_participants'];

                if ($currentParticipants < $maxParticipants) {
                    $userQuery = "SELECT full_name, email, contact_number FROM users WHERE id = ?";
                    $userStmt = $conn->prepare($userQuery);
                    $userStmt->bind_param("i", $userId);
                    $userStmt->execute();
                    $userResult = $userStmt->get_result();

                    if ($userResult->num_rows > 0) {
                        $user = $userResult->fetch_assoc();

                        $query = "INSERT INTO participants (userId, eventId, full_name, email, contact_number) VALUES (?, ?, ?, ?, ?)";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("iisss", $userId, $eventId, $user['full_name'], $user['email'], $user['contact_number']);
    
                        if ($stmt->execute()) {                         
                            $interaction_type = "join";
                            $logQuery = "INSERT INTO user_interactions (user_id, event_id, interaction_type) VALUES (?, ?, ?)";
                            $logStmt = $conn->prepare($logQuery);
                            $logStmt->bind_param("iis", $userId, $eventId, $interaction_type);
                            $logStmt->execute();
                            $logStmt->close();

                            //echo "<script>alert('You have joined in the event successfully!'); window.location.href = 'events.php';</script>";
                            $redirectUrl = 'events.php';
                            echo "<script>
                                document.querySelector('#successModal .modal-close-btn')
                                    .setAttribute('onclick', `closeModal('successModal', '$redirectUrl')`);
                                showModal('success', 'You have joined in the event successfully!');
                            </script>";
                        } else {
                            echo "Error: " . $stmt->error;
                        }
                        $stmt->close();
                    } else {
                        echo "User details not found";
                    }
                    $userStmt->close();
                } else {
                    //echo "<script>alert('The event has reached its maximum participant capacity.'); window.location.href = 'events.php'</script>";
                    $redirectUrl = 'events.php';
                    echo "<script>
                        document.querySelector('#failureModal .modal-close-btn')
                            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
                        showModal('failure', 'The event has reached its maximum participant capacity.');
                    </script>";
                }
                $countStmt->close();
            } else {
                //echo "<script>alert('Registration for this event is not open currently.'); window.location.href = 'events.php'</script>";
                $redirectUrl = 'events.php';
                echo "<script>
                    document.querySelector('#failureModal .modal-close-btn')
                        .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
                    showModal('failure', 'Registration for this event is not open currently.');
                </script>";
            }
        } else {
            echo "Event details not found.";
        }
        $checkStmt->close();
    } 
} else {
    echo "Invalid event ID.";
}
$conn->close();
?>
