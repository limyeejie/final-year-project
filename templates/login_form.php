<?php
session_start();
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
unset($_SESSION['errors']); 

include 'session_check.php';
redirect_if_logged_in();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/login_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/purecss@2.0.6/build/pure-min.css">
    <script>
            function showSuccessMessage(message) {
                alert(message);
            }
    </script>
</head>
<body>
    <!-- Header Section -->
    <?php include('header.php'); ?>

    <div class="login-container">
        <div class="login-content">
            <!-- Left Column - Logo with Animation -->
            <div class="floating-cloud">
                <img src="../images/logo.png" alt="Logo" class="logo-image">
            </div>

            <!-- Right Column - Login Form -->
            <div class="form-login">
                <h2 style="font-weight: bold; text-align: center;">Login</h2>
                <form method="POST" action="login.php">
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <!-- Email row -->
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <label for="email" style="width: 150px;">Email</label>
                            <input type="email" id="email" name="email" required style="flex: 1; padding: 8px;">
                        </div>
                        <span class="error-message"><?php echo isset($errors['email']) ? $errors['email'] : ''; ?></span>

                        <!-- Password row -->
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <label for="password" style="width: 150px;">Password</label>
                            <input type="password" id="password" name="password" required style="flex: 1; padding: 8px;">
                        </div>
                        <span class="error-message"><?php echo isset($errors['password']) ? $errors['password'] : ''; ?></span>
                        <!-- Submit Button -->
                        <div style="display: flex; gap: 10px; justify-content: flex-end;">
                            <button type="submit" style="padding: 6px 20px;">Login</button>
                        </div>
                    </div>
                </form>

                <!-- Additional Links -->
                <p>Don't have an account? <a href="signup_form.php" style="color: azure;">Sign up here</a></p>
                <p><a href="reset_password_form.php" style="color: azure;">Forgot Password?</a></p>
            </div>
        </div>
    </div>


    <?php
    // Check if the success message session variable is set
    if (isset($_SESSION['success_message'])) {
        // Display the popup with the success message
        echo "<script>showSuccessMessage('" . $_SESSION['success_message'] . "');</script>";
        // Unset the success message session variable so it doesn't show again
        unset($_SESSION['success_message']);
    }
    ?>

    <script src="../js/script.js"></script>
    <!-- Footer Section -->
    <?php include('footer.php'); ?>
</body>


</html>