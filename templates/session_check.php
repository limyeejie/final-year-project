<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function check_user_logged_in() {
    if (!isset($_SESSION["user_id"])) {
        header("location: gen.php");
        exit();
    }
}

function check_if_student() {
    check_user_logged_in(); // Ensure user is logged in before checking the role
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'Student') {
        header('Location: homepage.php');
        exit();
    }
}

function check_if_organizer() {
    check_user_logged_in(); // Ensure user is logged in before checking the role
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'Organizer') {
        header('Location: services.php');
        exit();
    }
}

function redirect_if_logged_in() {
    if (isset($_SESSION["user_id"])) {
        header("Location: homepage.php");
        exit();
    }
}

?>