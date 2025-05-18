<?php
include 'session_check.php';
include '../config/connection.php';

$currentDate = date('Y-m-d');


$sql = "SELECT * FROM volunteers WHERE date > ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $currentDate);
$stmt->execute();


$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/volunteer_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/purecss@2.0.6/build/pure-min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="../js/volunteerscript.js"></script>
</head>

<body>
    <!-- Header Section -->
    <?php include('header.php'); ?>

    <!-- Volunteer Section -->
    <div class="intro">
        <div class="image-belt-left">
            <div class="belt-track">
                <img src="../images/volunteer/event.jpg" alt="Image 1">
            </div>
        </div>
        <div class="intro-volunteer">
            <h1 class="intro-title">Become a Volunteer and Make a Difference</h1>
            <p class="intro-subtext">Join thousands of volunteers making an impact across Malaysia</p>
        </div>
        <div class="image-belt-left">
            <div class="belt-track">
                <img src="../images/volunteer/event2.png" alt="Image 2">
            </div>
        </div>
    </div>
    <!-- Why Volunteer Section -->
    <div class="why-volunteer">
        <h1>Why Volunteer</h1>
        <p class="section-subtitle"><em>Discover the joy and impact of giving your time and skills.</em></p>
        <div class="explain-container">
            <div class="explain" id="personal-growth">
                <div class="explain-inner">
                    <img src="../images/volunteer/life.png" alt="Personal Growth">
                    <h2>Personal Growth</h2>
                    <p class="description">
                        Learn new skills <br>
                        while giving<br>
                        back
                    </p>
                </div>
            </div>
            <div class="explain" id="community-impact">
                <div class="explain-inner">
                    <img src="../images/volunteer/community.png" alt="Community Impact">
                    <h2>Community Impact</h2>
                    <p class="description">
                        Contribute to<br>
                        meaningful<br>
                        cause
                    </p>
                </div>
            </div>
            <div class="explain" id="networking">
                <div class="explain-inner">
                    <img src="../images/volunteer/networking.png" alt="Networking">
                    <h2>Networking</h2>
                    <p class="description">
                        Meet people and<br>
                        expand your<br>
                        connections
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- Volunteer Opportunities Section -->
    <div class="volunteer-opportunity swiper">
        <h1>Volunteer Opportunities</h1>
        <p class="section-subtitle"><em>Explore exciting roles where you can make a difference.</em></p>
        <?php if ($result->num_rows > 0): ?>
            <div id="volunteer-container" class="slider-wrapper">
                <div class="card-list swiper-wrapper">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="card-item swiper-slide">
                            <h2 class="event-name"><?php echo htmlspecialchars($row['title']); ?></h2>
                            <p class="brief-description">
                                <?php echo htmlspecialchars(substr($row['description'], 0, 100)); ?>... <!-- Short description -->
                            </p>
                            <p class="date-location">
                                <?php echo htmlspecialchars($row['date']); ?> &bullet; <?php echo htmlspecialchars($row['location']); ?>
                            </p>
                            <button
                                class="opportunity-btn"
                                onclick="navigateToOpportunity(<?php echo htmlspecialchars($row['id']); ?>)">
                                See Opportunities
                            </button>
                        </div>
                    <?php endwhile; ?>
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next swiper-slide-button"></div>
                <div class="swiper-button-prev swiper-slide-button"></div>
            </div>
        <?php else: ?>
            <p>No volunteer opportunity found.</p>
        <?php endif; ?>
    </div>

    <!-- Volunteer Testimonials Section -->
    <div class="volunteer-testimonial" id="testimonial-section">
        <h1>What Volunteers Are Saying</h1>
        <p class="section-subtitle"><em>Hear from those who’ve made a difference.</em></p>
        <div class="volunteer-testimonial-slider" id="testimonial-slider">
            <div class="vol-testimonial" id="vol-testimonial1">
                <p>"This platform helped me find meaningful volunteering opportunities."</p>
                <span>- Sarah Sanders</span>
            </div>
            <div class="vol-testimonial" id="vol-testimonial2">
                <p>"A fantastic platform! Easy to use and fulfilling to volunteer."</p>
                <span>- John Doe</span>
            </div>
            <div class="vol-testimonial" id="vol-testimonial3">
                <p>"Great opportunities to give back—quick and seamless process!"</p>
                <span>- Jane Smith</span>
            </div>
        </div>
    </div>

    <!--back to top button-->
    <a href="#" id="back-to-top" title="Back to Top">&uarr;</a>
    <script src="../js/script.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <!-- Footer Section -->
    <?php include('footer.php') ?>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</body>

</html>