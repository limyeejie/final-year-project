<?php

session_start();
include '../config/connection.php';
include 'modal.php';
$userId = $_SESSION['user_id'];
$volunteerId = isset($_POST['volunteerId']) ? (int)$_POST['volunteerId'] : 0;

if ($volunteerId > 0) {

    // Check if user is already registered for the volunteer opportunity
    $checkQuery = "SELECT * FROM application_volunteer WHERE userId = ? AND volunteerId = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("ii", $userId, $volunteerId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        $redirectUrl = 'volunteer.php';
        echo "<script>
            document.querySelector('#failureModal .modal-close-btn')
                .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
            showModal('failure', 'You are already registered for this volunteer opportunity.');
        </script>";
        exit();
    } else {
        // Check the maximum volunteer cap for the opportunity
        $volunteerQuery = "SELECT max_volunteer FROM volunteers WHERE id = ?";
        $volunteerStmt = $conn->prepare($volunteerQuery);
        $volunteerStmt->bind_param("i", $volunteerId);
        $volunteerStmt->execute();
        $volunteerResult = $volunteerStmt->get_result();

        if ($volunteerResult->num_rows > 0) {
            $volunteer = $volunteerResult->fetch_assoc();
            $maxVolunteers = $volunteer['max_volunteer'];

            // Count current volunteers for the opportunity
            $countQuery = "SELECT COUNT(*) as current_volunteers FROM application_volunteer WHERE volunteerId = ?";
            $countStmt = $conn->prepare($countQuery);
            $countStmt->bind_param("i", $volunteerId);
            $countStmt->execute();
            $countResult = $countStmt->get_result();
            $countData = $countResult->fetch_assoc();
            $currentVolunteers = $countData['current_volunteers'];

            if ($currentVolunteers < $maxVolunteers) {
                // Get user details
                $userQuery = "SELECT full_name, email, contact_number FROM users WHERE id = ?";
                $userStmt = $conn->prepare($userQuery);
                $userStmt->bind_param("i", $userId);
                $userStmt->execute();
                $userResult = $userStmt->get_result();
    
                if ($userResult->num_rows > 0) {
                    $user = $userResult->fetch_assoc();
    
                    // Insert the new application
                    $query = "INSERT INTO application_volunteer (userId, volunteerId, full_name, email, contact_number) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("iisss", $userId, $volunteerId, $user['full_name'], $user['email'], $user['contact_number']);
    
                    if ($stmt->execute()) {
                        $redirectUrl = 'volunteer.php';
                        echo "<script>
                            document.querySelector('#successModal .modal-close-btn')
                                .setAttribute('onclick', `closeModal('successModal', '$redirectUrl')`);
                            showModal('success', 'You have successfully joined in the volunteer event!');
                        </script>";
                        exit();
                    } else {
                        echo "Error: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    echo "User details not found";
                }
                $userStmt->close();
            } else {
                //echo "The volunteer opportunity has reached its maximum capacity.";
                $redirectUrl = 'volunteer.php';
                echo "<script>
                    document.querySelector('#failureModal .modal-close-btn')
                        .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
                    showModal('failure', 'The volunteer opportunity has reached its maximum capacity.');
                </script>";
                exit();
            }
            $countStmt->close();
        } else {
            echo "Volunteer opportunity details not found.";
        }
        $volunteerStmt->close();
    }
    $checkStmt->close();
} else {
    echo "Invalid volunteer ID.";
}
$conn->close();
?>
