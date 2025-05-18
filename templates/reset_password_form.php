<?php
session_start();
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
unset($_SESSION['errors']); 
$message = isset($_SESSION['message']) ? $_SESSION['message'] : [];
unset($_SESSION['message']); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/reset_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/purecss@2.0.6/build/pure-min.css">
</head>
<body>
    <!-- Header Section -->
    <?php include('header.php'); ?>

    <div class="reset-password-container">
    <div class="reset-password-content">
        <!-- Left Column - Reset Form -->
        <div class="form-reset-password">
    <h2>Reset Password</h2>
    <form method="POST" action="reset_password.php">
        <div style="display: flex; flex-direction: column; gap: 15px;">
            <!-- Email row -->
            <div style="display: flex; flex-direction: column;">
                <div style="display: flex; flex-direction: row; gap: 5px;">
                    <label for="email" style="width: 150px;">Email</label>
                    <input type="email" id="email" name="email" required style="flex: 1; padding: 8px;"placeholder="e.g.,example@domain.com">
                </div>
                <span class="error-message" style="color: red;"><?php echo isset($errors['email']) ? $errors['email'] : ''; ?></span>
            </div>

            <!-- New Password row -->
            <div style="display: flex; flex-direction: column;">
                <div style="display: flex; flex-direction: row; gap: 5px;">
                    <label for="new-password" style="width: 150px;">New Password</label>
                    <input type="password" id="new-password" name="new-password" required style="flex: 1; padding: 8px;"placeholder="At least 8 characters">
                </div>
                <span class="error-message" style="color: red;"><?php echo isset($errors['new-password']) ? $errors['new-password'] : ''; ?></span>
            </div>

            <!-- Confirm Password row -->
            <div style="display: flex; flex-direction: column;">
                <div style="display: flex; flex-direction: row; gap: 5px;">
                    <label for="confirm-password" style="width: 150px;">Confirm New Password</label>
                    <input type="password" id="confirm-password" name="confirm-password" required style="flex: 1; padding: 8px;"placeholder="Re-enter password">
                </div>
                <span class="error-message" style="color: red;"><?php echo isset($errors['confirm-password']) ? $errors['confirm-password'] : ''; ?></span>
            </div>

            <span class="error-message" style="color: red; text-align: center;"><?php echo isset($errors['password']) ? $errors['password'] : ''; ?></span>

            <!-- Submit Button -->
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="submit" style="padding: 6px 20px;">Reset Password</button>
            </div>
        </div>
    </form>

    <!-- Additional Links -->
    <p>Remembered your password? <a href="login_form.php" style="color:darkviolet;">Log in here</a></p>
</div>

        <!-- Right Column - Logo with Animation -->
        <div class="floating-cloud">
            <img src="../images/logo.png" alt="Logo" class="logo-image">
        </div>
    </div>
</div>
<script src="../js/script.js"></script>
    <!-- Footer Section -->
    <?php include('footer.php'); ?>
    
</body>
</html>
