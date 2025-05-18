<?php

session_start();

include "../config/connection.php";
include 'modal.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $new_password = $_POST['new-password'];
    $confirm_password = $_POST['confirm-password'];
    $errors = [];
    $messages = [];

    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    } 
    if (empty($new_password)) {
        $errors['new-password'] = "New password is required.";
    } elseif (
        strlen($new_password) < 8 ||                          
        !preg_match('/[A-Z]/', $new_password) ||          
        !preg_match('/[a-z]/', $new_password) ||          
        !preg_match('/\d/', $new_password) ||                 
        !preg_match('/[\W_]/', $new_password)                
    ) {
        $errors['new-password'] = "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.";
    }


    if (empty($confirm_password)) {
        $errors['confirm-password'] = "Confirm password is required.";
    }

    if ($new_password !== $confirm_password) {
        $errors['password'] = "Passwords do not match.";
    }

    if(empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows > 0) {
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);


            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $update_stmt->bind_param("ss", $hashed_password, $email);
            if ($update_stmt->execute()) {
                //$_SESSION['success_message'] = "Password has changed successfully!";
                $redirectUrl = 'login_form.php';
                echo "<script>
                document.querySelector('#successModal .modal-close-btn')
                    .setAttribute('onclick', `closeModal('successModal', '$redirectUrl')`);
                showModal('success', 'Password has changed successfully!');
                </script>";    
            } else {
                header("Location: reset_password_form.php");
                exit();
            }
            $update_stmt->close();

        } else {
            $errors['email'] = "Email not found.";
            $_SESSION['errors'] = $errors;
            header("Location: reset_password_form.php");
            exit();
        }
        $stmt->close();
    } else {
        $_SESSION['errors'] = $errors;
        header('Location: reset_password_form.php');
        exit();
    }
}
?>