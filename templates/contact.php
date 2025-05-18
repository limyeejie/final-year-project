<?php
session_start();
include 'modal.php';
include '../config/connection.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fullname =  $_POST['fullname'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $reason = $_POST['reasonContact'];
    $role = $_POST['role'];
    $message = $_POST['message'];

    $errors = [];

        // Validate form data
        if (empty($fullname) || preg_match('/[0-9]/', $fullname)) {
            $errors['fullname'] = 'Full Name is required and cannot contain numbers';
        } elseif (!preg_match("/^[a-zA-Z\s'-]+$/", $fullname)) {
            $errors['fullname'] = 'Full Name can only contain letters, spaces, hyphens, and apostrophes.';
        }
    
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Valid email is required.';
        }
        
        if (empty($subject)) {
            $errors['subject'] = 'Subject is required.';
        }
        
        if (empty($message)) {
            $errors['message'] = 'Message is required.';
        }
        if (empty($reason)) {
            $errors['reasonContact'] = 'Reason is required.';
        }
        if (empty($role)) {
            $errors['role'] = 'Role is required.';
        }

        
        if (empty($errors)) {
            $stmt = $conn->prepare("INSERT INTO contact_us (fullname, email, subject, reason, role, message) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $fullname, $email, $subject, $reason, $role, $message);
            if ($stmt->execute()) {
                //header("Location: contact_form.php");
                $redirectUrl = 'contact_form.php';
                echo "<script>
                document.querySelector('#successModal .modal-close-btn')
                    .setAttribute('onclick', `closeModal('successModal', '$redirectUrl')`);
                showModal('success', 'Feedback has been sent');
                </script>";
            } else {
                
                header("Location: contact_form.php");
                exit();
            }
            $stmt->close();


        } else {
            $_SESSION['errors'] = $errors;
        }


}

?>