<?php

session_start();
include 'participation_notification_check.php';
include '../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password']; 

    $errors = [];
    $message = [];

    // Validate email
    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    // Validate password
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    }

    if (empty($errors)) {
        // Check if the user exists
        $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $hashed_password, $role);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Set session variables
                $_SESSION['user_id'] = $user_id;
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $role;

                // Redirect based on role
                if ($role == 'Student') {
                    header('Content-Type: application/json');
                    echo json_encode(sendLowParticipationNotifications($conn));
                    header('Location: homepage.php');
                } elseif ($role == 'Organizer') {
                    header('Location: services.php');
                } elseif ($role == 'Admin') {
                    header('Location: admin_dashboard.php');
                }
                exit();
            } else {
                $_SESSION['errors']['password'] = "Invalid password.";
            }
        } else {
            $_SESSION['errors']['email'] = "Invalid email";
        }
        $stmt->close();
    } else {
        $_SESSION['errors'] = $errors;
    }

    // Redirect back to the login form if there are errors
    header("Location: login_form.php");
    exit();
}

$conn->close();
?>
