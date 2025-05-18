<?php

session_start();

include '../config/connection.php';
include 'modal.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $full_name = $_POST['full-name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirmpassword'];
    $role = isset($_POST['role']) ? $_POST['role'] : null;
    $contact_number = $_POST['phone'];
    $date_of_birth = $_POST['dateOfBirth'];
    $gender = isset($_POST['gender']) ? $_POST['gender'] : null;
    $terms = isset($_POST['terms']) ? $_POST['terms'] : null;

    $errors = [];

    if (empty($full_name) || preg_match('/[0-9]/', $full_name)) {
        $errors['full-name'] = 'Full Name is required and cannot contain numbers';
    } elseif (!preg_match("/^[a-zA-Z\s'-]+$/", $full_name)) {
        $errors['full-name'] = 'Full Name can only contain letters, spaces, hyphens, and apostrophes.';
    }

    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Invalid email format.";
    } else {
        $emailCheckStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $emailCheckStmt->bind_param("s", $email);
        $emailCheckStmt->execute();
        $emailCheckStmt->store_result();

        if($emailCheckStmt->num_rows > 0) {
            $errors['email'] = "Email already exists. Please use a different email.";
        }
        $emailCheckStmt->close();
    }

    if (empty($password)) {
        $errors['password'] = "Password is required.";
    } elseif (
        strlen($password) < 8 ||                          
        !preg_match('/[A-Z]/', $password) ||          
        !preg_match('/[a-z]/', $password) ||          
        !preg_match('/\d/', $password) ||                 
        !preg_match('/[\W_]/', $password)                
    ) {
        $errors['password'] = "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.";
    }

    if ($password !== $confirm_password) {
        $errors['confirm_password'] = "Passwords do not match.";
    }

    if (empty($contact_number)) {
        $errors["contact_number"] = "Contact Number is required.";
    } elseif (!preg_match("/^\+60\d{9,10}$/", $contact_number)) {
        $errors["contact_number"] = "Contact Number must start with +60 and be followed by 9 or 10 digits.";
    }

    if (empty($date_of_birth)) {
        $errors['date_of_birth'] = "Date of Birth is required.";
    } else {
        $dob = new DateTime($date_of_birth);
        $currentDate = new DateTime();
        $age = $currentDate->diff($dob)->y;

        if ($dob > $currentDate) {
            $errors['date_of_birth'] = "Date of Birth cannot be in the future.";
        } else if ($age < 18) {
            $errors['date_of_birth'] = "You must be at least 18 years old to register.";
        }
    }

    $allowedGenders = ['Male', 'Female', 'Other'];
    if (empty($gender)) {
        $errors["gender"] = "Gender is required.";
    } elseif (!in_array($gender, $allowedGenders)) {
        $errors["gender"] = "Invalid gender selected.";
    }

    $allowedRoles = ['Student', 'Organizer'];
    if (empty($role)) {
        $errors['role'] = "Role is required.";
    } elseif (!in_array($role, $allowedRoles)) {
        $errors['role'] = "Invalid role selected.";
    }

    if (!$terms) {
        $errors['terms'] = "You must agree to the Terms and Conditions to register.";
    }

    if (empty($errors)) {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role, contact_number, date_of_birth, gender) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $full_name, $email, $hashed_password, $role, $contact_number, $date_of_birth, $gender);


        if ($stmt->execute()) {
            //$_SESSION['success_message'] = "Account created successfully!";
            $redirectUrl = 'login_form.php';
            echo "<script>
            document.querySelector('#successModal .modal-close-btn')
                .setAttribute('onclick', `closeModal('successModal', '$redirectUrl')`);
            showModal('success', 'Account created successfully.');
            </script>";

            $userId = $stmt->insert_id; // Get the newly created user's ID

            // Set default preferences (setting) for a student
            if ($role == 'Student') {
                $defaultAlert = 1;
                $defaultNews = 1;
                $defaultRecommend = 1;
    
                $prefstmt = $conn->prepare("INSERT INTO profiles (userId, alert, news, recommend) VALUES (?, ?, ?, ?)");
                $prefstmt->bind_param("iiii", $userId, $defaultAlert, $defaultNews, $defaultRecommend);
    
                if ($prefstmt->execute()) {
                    //echo "User registered successfully with default preferences.";
                } else {
                    echo "Error setting default preferences: " . $prefstmt->error;
                }
                $prefstmt->close();
            }
        } else {
            echo "Error: " , $stmt->error;
        }

        $stmt->close();
        $conn->close();


    } else {
        $_SESSION['errors'] = $errors;
        header("Location: signup_form.php");
        exit();
    }



}



?>