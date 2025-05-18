<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- header.php -->
<header class="bg-light p-3">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center logo-container">
            <?php if (isset($_SESSION['role'])): ?>
                <?php if ($_SESSION['role'] === 'Student'): ?>
                    <a href="gen.php">
                        <img src="../images/logo.png" alt="SphereConnect Logo" class="logo">
                    </a>
                <?php elseif ($_SESSION['role'] === 'Organizer'): ?>
                    <a href="gen.php">
                        <img src="../images/logo.png" alt="SphereConnect Logo" class="logo">
                    </a>
                <?php endif; ?>
            <?php else: ?>
                <a href="gen.php">
                    <img src="../images/logo.png" alt="SphereConnect Logo" class="logo">
                </a>
            <?php endif; ?>
            <h3>SPHERECONNECT</h3>
        </div>
        
        <nav>
            <ul class="nav">
                <?php if (isset($_SESSION['user_id']) && isset($_SESSION['role'])): ?>
                    <?php if ($_SESSION['role'] === 'Student'): ?>
                        <li class="nav-item"><a href="homepage.php" class="nav-link text-black"><i class="fas fa-home"></i> Home</a></li>
                        <li class="nav-item"><a href="events.php" class="nav-link text-black"><i class="fas fa-calendar-alt"></i> Events</a></li>
                        <li class="nav-item"><a href="volunteer.php" class="nav-link text-black"><i class="fas fa-hands-helping"></i> Volunteer</a></li>
                        <li class="nav-item"><a href="contact_form.php" class="nav-link text-black"><i class="fas fa-envelope"></i> Contact Us</a></li>
                        <li class="nav-item"><a href="profile.php" class="nav-link text-black"><i class="fas fa-user"></i> Profile</a></li>
                        <li class="nav-item"><a href="logout.php" class="nav-link text-black"><i class="fas fa-sign-out-alt"></i> Logout</a></li>

                    <?php elseif ($_SESSION['role'] === 'Organizer'): ?>
                        <li class="nav-item"><a href="services.php" class="nav-link text-black"><i class="fas fa-home"></i> Home</a></li>
                        <li class="nav-item"><a href="management.php" class="nav-link text-black"><i class="fas fa-calendar-alt"></i> Events</a></li>
                        <li class="nav-item"><a href="contact_form.php" class="nav-link text-black"><i class="fas fa-envelope"></i> Contact Us</a></li>
                        <li class="nav-item"><a href="profile.php" class="nav-link text-black"><i class="fas fa-user"></i> Profile</a></li>
                        <li class="nav-item"><a href="logout.php" class="nav-link text-black"><i class="fas fa-sign-out-alt"></i> Logout</a></li>

                    <?php endif; ?>
                <?php else: ?>
                    <!-- For guests who are not logged in -->
                    <li class="nav-item"><a href="gen.php" class="nav-link text-black"><i class="fas fa-home"></i> Home</a></li>
                    <li class="nav-item"><a href="login_form.php" class="nav-link text-black"><i class="fas fa-user"></i> Login</a></li>
                    <li class="nav-item"><a href="signup_form.php" class="nav-link text-black"><i class="fas fa-user-plus"></i> Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="hamburger">
            <i class="fas fa-bars"></i>
        </div>
    </div>
</header>
