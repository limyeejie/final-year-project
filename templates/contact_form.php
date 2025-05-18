<?php

include 'session_check.php';
check_user_logged_in();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
unset($_SESSION['errors']); 
$feedback = isset($_SESSION['feedback']) ? $_SESSION['feedback'] : [];
unset($_SESSION['message']); 
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Contact Us</title>
        <link rel="stylesheet" href="../css/styles.css">
        <link rel="stylesheet" href="../css/contact_styles.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" href="https://unpkg.com/purecss@2.0.6/build/pure-min.css">
    </head>
    <body>
        <!-- Header Section -->
        <?php include('header.php'); ?>

        <!-- Contact Us -->
        <div class="contact-us" style="display: flex; flex-direction: column; justify-content: center; align-items: center; padding-bottom: 50px; background: linear-gradient(to right, #90d6ff, white, #cf9fff); min-height: 89vh;">
            <h1 style="font-size: 48px; font-weight: bold; text-align: center; padding-top: 50px;">Contact Us</h1>
            <div class="contact-wrapper">
                <div class="contact-container" style="background-color: #90D6FF; padding: 20px; border-radius: 15px; animation: slide-in-left 1s ease-out;">
                    <p>Do you have any questions, suggestions, or feedback? <br/>
                        We'd love to hear from you! Feel free to reach out to us
                        through the form below or via any of our contact details.
                    </p>

                    <p>
                        Contact Information: <br/>
                        <ul>
                            <li>Email: support_sc@gmail.com</li>
                            <li>Phone: +604-2233445</li>
                            <li>Address: INFINITY8 Reserve George Town, 2, Beach St, Georgetown, 10300 George Town, Penang</li>
                            <!-- add google map location -->
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3971.9894539324428!2d100.34073707479668!3d5.418571294560665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x304ac30118d18afb%3A0xf165fb356f247815!2sINFINITY8%20Reserve%20George%20Town!5e0!3m2!1sen!2smy!4v1732332060004!5m2!1sen!2smy" 
                                width="350" height="200" style="border:0;" allowfullscreen="" 
                                loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            <li>Working Hours: Monday to Friday, 9:00 AM - 5:00 PM</li>
                        </ul>
                    </p>
                </div>

                <!-- contact form -->
                <div class="contact-container" style="background-color: #CF9FFF; padding: 20px; border-radius: 15px; animation: slide-in-right 1s ease-out;">
                <h2 style="font-weight: bold; text-align: center;">CONTACT US FORM</h2>
                <form id="contact-form" method="POST" action="contact.php">
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                            <!-- Full name row -->
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <label for="fullname" style="width: 150px;">Full Name</label>
                                <input type="text" id="fullname" name="fullname" style="flex: 1; padding: 8px;"placeholder="Enter your full name">
                                <span class="error-message" style="color: red;"><?php echo isset($errors['fullname']) ? $errors['fullname'] : ''; ?></span>
                            </div>

                            <!-- Email row -->
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <label for="email" style="width: 150px;">Email</label>
                                <input type="email" id="email" name="email" style="flex: 1; padding: 8px;"placeholder="Enter your email address">
                                <span class="error-message" style="color: red;"><?php echo isset($errors['email']) ? $errors['email'] : ''; ?></span>
                            </div>

                            <!-- Subject row -->
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <label for="subject" style="width: 150px;">Subject</label>
                                <input type="text" id="subject" name="subject" style="flex: 1; padding: 8px;"placeholder="Enter a subject">
                                <span class="error-message" style="color: red;"><?php echo isset($errors['subject']) ? $errors['subject'] : ''; ?></span>
                            </div>

                            <!-- Reason row -->
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <label for="reasonContact" style="width: 150px;">Reason For Contact</label>
                                <input type="text" id="reasonContact" name="reasonContact" style="flex: 1; padding: 8px;"placeholder="State reason for contact">
                                <span class="error-message" style="color: red;"><?php echo isset($errors['reasonContact']) ? $errors['reasonContact'] : ''; ?></span>
                            </div>

                            <!-- Role row -->
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <label for="role" style="width: 150px;">Role</label>
                                <input type="text" id="role" name="role" style="flex: 1; padding: 8px;"placeholder="Your role or title">
                                <span class="error-message" style="color: red;"><?php echo isset($errors['role']) ? $errors['role'] : ''; ?></span>
                            </div>

                            <!-- Message Row -->
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <label for="message" style="width: 150px;">Message</label>
                                <textarea id="message" name="message" rows="4" cols="50" style="flex: 1; padding: 8px;"placeholder="Enter your message"></textarea>
                                <span class="error-message" style="color: red;"><?php echo isset($errors['message']) ? $errors['message'] : ''; ?></span> 
                            </div>

                            <!-- Clear and submit buttons -->
                            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                                <button type="reset" style="padding: 6px 20px; background-color: blue; color: white; border-color: transparent; border-radius: 0.5rem;">Clear</button>
                                <button type="submit" style="padding: 6px 20px; background-color: blue; color: white; border-color: transparent; border-radius: 0.5rem;">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script src="../js/script.js"></script>
        
        <!-- Footer Section -->
        <?php include('footer.php'); ?>
    </body>
</html>