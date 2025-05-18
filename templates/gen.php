<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SphereConnect - Welcome</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/gen.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/purecss@2.0.6/build/pure-min.css">
</head>
<body>
    <!-- Header Section -->
    <?php include('header.php'); ?>
    <!-- Hero Section -->
    <div class="hero-section">
        <!-- 3D Network Canvas (Background) -->
        <canvas id="networkCanvas"></canvas>
    
        <!-- Welcome Text Section (Centering) -->
        <div class="welcome-text">
            <h1>Welcome to SphereConnect</h1>
            <p>Connecting students and communities through meaningful events and volunteering opportunities.</p>
            <a href="../templates/login_form.php">
                <button class="cta-button">Explore Now</button>
            </a>

        </div>
    </div>

    <!-- 3D Z-Index Layered Cards Section -->
    <div class="features-section">
        <h2>Build Better Communities with SphereConnect</h2>
        <p class="features-subtitle">Explore the unique benefits that our platform offers to both event organizers and participants.</p>
        <br>
        <div class="features-container">
            <div class="feature-card" style="transform: translateZ(50px);">
                <h3>Create Connections</h3>
                <p>Link communities and universities through collaborative events and volunteer opportunities.</p>
            </div>
            <div class="feature-card" style="transform: translateZ(40px);">
                <h3>Discover Your Purpose</h3>
                <p>Find events that match your interests and contribute to causes that matter.</p>
            </div>
            <div class="feature-card" style="transform: translateZ(30px);">
                <h3>Organize Impactful Events</h3>
                <p>Empower local organizers to create meaningful community connections and events.</p>
            </div>
            <div class="feature-card" style="transform: translateZ(20px);">
                <h3>Track and Celebrate Achievements</h3>
                <p>Earn recognition through badges and track your impact as you participate in events.</p>
            </div>
            <div class="feature-card" style="transform: translateZ(10px);">
                <h3>Build Skills and Network</h3>
                <p>Grow personally and professionally by volunteering, organizing, and connecting with others.</p>
            </div>
        </div>
    </div>

    <!-- 3D Carousel -->
    <div class="gallery-section">
        <br><br><br>
      <h2 class="section-title">Discover Our Impact</h2>
      <p class="section-subtitle">A glimpse into the moments that bring us together and make a difference</p>
      <div class="camera">
        <div class="swiper">
          <div class="d-carousel">
            <img src="../images/homepage/cd2.jpg" alt="" class="d-carousel-item">
            <img src="../images/homepage/cd3.jpg" alt="" class="d-carousel-item">
            <img src="../images/homepage/cd4.jpg" alt="" class="d-carousel-item">
            <img src="../images/homepage/cd7.jpg" alt="" class="d-carousel-item">
            <img src="../images/homepage/cd8.jpg" alt="" class="d-carousel-item">
            <img src="../images/services page/slider2.jpg" alt="" class="d-carousel-item">
            <img src="../images/services page/slider3.jpg" alt="" class="d-carousel-item">
            <img src="../images/services page/slider4.jpg" alt="" class="d-carousel-item">
            <img src="../images/volunteer/volunteer.jpg" alt="" class="d-carousel-item">
          </div>
        </div>
      </div>
    </div>

    <!-- 3D Text Animation Section -->
<div class="text-animation-section">
    <h2>Join Our Mission</h2>
    <h3 class="section-subtitle">Together, we make a difference</h3>
    <div class="text-container">
        <div class="text-item">
            <i class="fas fa-users fa-3x"></i>
            <h3 class="text-slide">Transform your community</h3>
            <p class="description">Join hands with local initiatives to create lasting change.</p>
        </div>
        <!-- Single Back-and-Forth Arrow -->
        <i class="fas fa-arrows-alt-h double-arrow-icon"></i> 
        <div class="text-item">
            <i class="fas fa-calendar-alt fa-3x"></i>
            <h3 class="text-slide">One event at a time</h3>
            <p class="description">Every event helps build a stronger, more connected community.</p>
        </div>
        <!-- Single Back-and-Forth Arrow -->
        <i class="fas fa-arrows-alt-h double-arrow-icon"></i> 
        <div class="text-item">
            <i class="fas fa-handshake fa-3x"></i>
            <h3 class="text-slide">Be part of something bigger</h3>
            <p class="description">Empower yourself by supporting causes that matter most.</p>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"></script>
    <!-- JavaScript for 3D Effects -->
    <script src="../js/gen.js"></script>
    <script src="../js/script.js"></script>
    <!--back to top button-->
    <a href="#" id="back-to-top" title="Back to Top">&uarr;</a>
    <!-- Footer Section -->
    <?php include('footer.php'); ?>

</body>
</html>
