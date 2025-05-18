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
    <title>Sign Up Page</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/signup_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/purecss@2.0.6/build/pure-min.css">
</head>
<body>

<?php include('header.php'); ?>

<div class="row-sign-up" style="height: 89vh;">
    <div class="sign-up">
        <div class="form-sign-up">
            <h2 style="text-align:center">Registration</h2>
            <h4 style="text-align:center">Welcome to SPHERECONNECT</h4>
            <p class="required-note" style="text-align:center">Required fields are marked with an asterisk *</p>
            
            <!-- Two columns form -->
            <form id="form" method="POST" action="signup.php" style="animation: slide-up 1s ease-out;">
                <div class="form-columns">
                    <!-- First Column -->
                    <div class="column">
                        <!--1. Full Name-->
                        <div style="clear:both">
                            <label style="width:200px;">* Full Name</label>
                            <input type="text" id="full-name" name="full-name" class="input-gap" style="width:300px;"placeholder="Enter your full name (letters only)">
                            <span id="s1" class="error-message"><?php echo isset($errors['full-name']) ? $errors['full-name'] : ''; ?></span>
                        </div>
                        <br>
                        <!--3. Password-->
                        <div style="clear:both;">
                            <label style="width:200px;">* Password</label>
                            <input type="password" id="password" name="password" class="input-gap" style="width:300px;"placeholder="At least 8 characters">
                            <span id="s3" class="error-message"><?php echo isset($errors['password']) ? $errors['password'] : ''; ?></span>
                        </div>
                        <br>
                        <!--5. Role-->
                        <div class="radio-group" style="clear:both;">
                            <label style="width:200px;">* Role:</label>
                            <div class="radio-buttons">
                                <input type="radio" name="role" id="role1" value="Student">Student
                                <input type="radio" name="role" id="role2" value="Organizer">Organizer
                            </div>
                            <span id="s5" class="error-message"><?php echo isset($errors['role']) ? $errors['role'] : ''; ?></span>
                        </div>
                        <br>
                        <!--7. Date of Birth-->
                        <div style="clear:both;">
                            <label style="width:200px;">* Date of Birth:</label>
                            <input type="date" id="dateOfBirth" name="dateOfBirth"placeholder="Select your date of birth">
                            <span id="s7" class="error-message"><?php echo isset($errors['date_of_birth']) ? $errors['date_of_birth'] : ''; ?></span>
                        </div>
                        <br>
                        <!--6. Contact Number-->
                        <div style="clear:both;">
                            <label style="width:200px;">* Contact Number</label>
                            <input type="tel" id="phone" name="phone" class="input-gap" style="width:300px;"placeholder="e.g., +60XXXXXXXXX (10-11 digits)">
                            <span id="s6" class="error-message"><?php echo isset($errors['contact_number']) ? $errors['contact_number'] : ''; ?></span>
                        </div>
                    </div>

                    <!-- Second Column -->
                    <div class="column">
                        <!--2. Email-->
                        <div style="clear:both;">
                            <label style="width:200px;">* Email</label>
                            <input type="email" id="email" name="email" class="input-gap" style="width:300px;"placeholder="e.g., example@domain.com">
                            <span id="s2" class="error-message"><?php echo isset($errors['email']) ? $errors['email'] : ''; ?></span>
                        </div>
                        <br>
                        <!--4. Confirm Password-->
                        <div style="clear:both;">
                            <label style="width:200px;">* Confirm Password</label>
                            <input type="password" id="confirmpassword" name="confirmpassword" class="input-gap" style="width:300px;"placeholder="Re-enter your password">
                            <span id="s4" class="error-message"><?php echo isset($errors['confirm_password']) ? $errors['confirm_password'] : ''; ?></span>
                        </div>
                        <br>
                        <!--8. Gender-->
                        <div class="radio-group" style="clear:both;">
                            <label style="width:200px;">* Gender:</label>
                            <div class="radio-buttons">
                                <input type="radio" name="gender" id="gender1" value="Male">Male
                                <input type="radio" name="gender" id="gender2" value="Female">Female
                                <input type="radio" name="gender" id="gender3" value="Other">Other
                            </div>
                            <span id="s8" class="error-message"><?php echo isset($errors['gender']) ? $errors['gender'] : ''; ?></span>
                        </div>
                        <br>
                        <!-- Terms & Conditions -->
                        <div style="clear:both;">
                            <input type="checkbox" id="terms" name="terms" style="float:left; margin-right: 10px;">
                            <label for="terms" style="float:left; width: auto;">* I agree to the <a href="#" id="tnc-link" style="color: darkgreen;">Terms and Conditions</a></label>
                            <span id="s9" class="error-message"><?php echo isset($errors['terms']) ? $errors['terms'] : ''; ?></span>
                        </div>
                        <br><br>
                        <!-- Buttons -->
                <div class="btn-container">
                    <button type="submit" class="btn btn-primary" id="submit" name="submit">Sign Up</button>
                    <button type="reset" class="btn btn-secondary">Clear All</button>
                </div>
                    </div>
                </div>

                <!-- T&C Modal -->
                <div id="tnc-modal" class="modal">
                    <div class="modal-content">
                         <span class="close-btn">&times;</span>
                         <h2>Terms and Conditions</h2>
                         <div class="modal-body">
                            <p>Last Updated: 20 October 2024 <br> Welcome to SphereConnect. By using our platform, you agree to comply with and be bound by the following terms and conditions, which govern your access to and use of the website, mobile application, and related services provided by SphereConnect.
                            <br>Please read these terms carefully before using the platform.</p>
                            <p>1. Acceptance of Terms<br>By registering for, accessing, or using SphereConnect, you agree to these Terms and Conditions. If you do not agree, you may not access or use the platform.</p>
                            <p>2. User Accounts<br>• Account Registration: To use certain features of SphereConnect, you must create an account by providing accurate and complete information. You are responsible for maintaining the confidentiality of your account credentials.
                            <br>• Account Security: You are responsible for all activities under your account. If you suspect any unauthorized use, you must notify us immediately.
                            <br>• Eligibility: You must be at least 18 years old to create an account on SphereConnect. Users under 18 must have parental or guardian consent.</p>
                            <p>3. Event Creation and Participation <br> • Creating Events: Event organizers are responsible for the accuracy of the event details they provide, including date, location, description, and any relevant terms for participation.
                            <br>• Event Participation: By signing up for events, users agree to comply with any event-specific terms set by the organizer. SphereConnect is not responsible for the outcome or experience of any event.
                            <br>• Cancellations: Event organizers may cancel events at their discretion. Users are encouraged to check event status regularly to avoid any inconvenience.</p>
                            <p>4. Community Services and Volunteer Opportunities <br> • Offering Services: If you are offering community services or volunteer opportunities through SphereConnect, you are responsible for providing accurate information and fulfilling any commitments made to users. 
                            <br> • Participation: Users engaging in volunteer opportunities agree to adhere to any terms set by the service provider and must act responsibly.</p>
                            <p>5. User Conduct <br> • Prohibited Activities: Users agree not to: 
                            <br> o Post or share misleading, defamatory, or illegal content. 
                            <br> o Engage in any activity that disrupts or interferes with the operation of SphereConnect.
                            <br> o Use the platform to harass, abuse, or harm other users. 
                            <br> • Community Guidelines: Users must follow our community guidelines, ensuring respectful and positive interactions within the platform.</p>
                            <p>6. Privacy Policy <br> • By using SphereConnect, you agree to the collection, storage, and use of your data as outlined in our Privacy Policy. We are committed to protecting your personal information and ensuring its confidentiality.</p>
                            <p>7. Intellectual Property <br> • Ownership: All content and intellectual property rights on the platform, including the website design, graphics, and logos, are owned by SphereConnect. You may not use or reproduce any material without permission.
                            <br> • User Content: Users retain ownership of content they upload to the platform. By submitting content, you grant SphereConnect a license to display, distribute, and promote the content as part of the service.</p>
                            <p>8. Liability and Disclaimers <br> • No Warranty: SphereConnect is provided on an "as-is" basis. We make no warranties or representations about the accuracy or completeness of the content or services provided.
                            <br> • Limitation of Liability: SphereConnect will not be liable for any indirect, incidental, or consequential damages arising from your use of the platform. Our liability is limited to the maximum extent permitted by law.
                            <br> • Event Liability: SphereConnect is a platform for connecting users and organizing events. We are not responsible for the quality, safety, legality, or conduct of any event or community service.</p>
                            <p>9. Termination <br> SphereConnect reserves the right to suspend or terminate your account at our discretion if you violate these Terms and Conditions. You may terminate your account at any time by contacting us.</p>
                            <p>10. Amendments to Terms <br> We may update these Terms and Conditions from time to time. Any changes will be posted on this page, and continued use of the platform constitutes acceptance of the updated terms.</p>
                         </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="sign-up">
        <img src="../images/logo.png" alt="log-new" class="floating-cloud">
    </div>
</div>

    <?php include('footer.php'); ?>
    <script src="../js/signupscript.js"></script>
    <script src="../js/script.js"></script>
</body>
</html>