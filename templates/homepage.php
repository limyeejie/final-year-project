
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/home_styles.css">
    <link rel="stylesheet" href="../css/chatbot_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/purecss@2.0.6/build/pure-min.css">
</head>
<body>
    <!-- Header Section -->
    <?php include('header.php'); ?>
    <!-- Chatbot Section -->
    <?php include('chatbot_response.php'); ?>
    <!-- Hero Section -->
    <div class="hero-section" id="hero-section">
        <div class="cd-row">
            <div class="cd">
                <div class="cd-inner">
                    <img src="../Images/homepage/cd1.jpg" alt="CD Image 1">
                </div>
            </div>
            <div class="cd">
                <div class="cd-inner">
                    <img src="../Images/homepage/cd2.jpg" alt="CD Image 2">
                </div>
            </div>
            <div class="cd">
                <div class="cd-inner">
                    <img src="../Images/homepage/cd3.jpg" alt="CD Image 3">
                </div>
            </div>
            <div class="cd">
                <div class="cd-inner">
                    <img src="../Images/homepage/cd4.jpg" alt="CD Image 4">
                </div>
            </div>
            <div class="cd">
                <div class="cd-inner">
                    <img src="../Images/homepage/cd5.jpg" alt="CD Image 5">
                </div>
            </div>
            <div class="cd">
                <div class="cd-inner">
                    <img src="../Images/homepage/cd6.jpg" alt="CD Image 6">
                </div>
            </div>
            <div class="cd">
                <div class="cd-inner">
                    <img src="../Images/homepage/cd7.jpg" alt="CD Image 7">
                </div>
            </div>
            <div class="cd">
                <div class="cd-inner">
                    <img src="../Images/homepage/cd8.jpg" alt="CD Image 8">
                </div>
            </div>
        </div>

        <div class="right-text side-text">
            <h1 class="hero-title">Engage in</h1>
            <h1 class="hero-title">Community Services & Events</h1>
            <p class="hero-subtext">Discover events and volunteer opportunities</p>
            <p class="hero-subtext">tailored to university students and the local community!</p>
            <!-- <button class="cta-button">Get Started!</button> -->
        </div>
    </div>

    <!-- AI-Driven Just For You Section -->
    <div class="ai-recommendations">
        <h1>Just For You</h1>
        <p>Based on your preferences, we’ve handpicked these events for you to explore.</p>
        <div id="recommendation-container" class="recommendation-container">
        </div>
    </div>

    <!-- Volunteer Badge System Section -->
    <div class="volunteer-badges">
        <h1>Earn Badges</h1>
        <p>Unlock exciting badges as you contribute to your community and grow as a volunteer!</p>
        <div class="badge-container">
            <div class="badge" id="badge1">
                <div class="badge-inner">
                    <img src="../Images/homepage/bronze.png" alt="Badge 1">
                    <h2>First Event</h2>
                    <p class="badge-description">Your journey starts here! <br>
                        This badge celebrates your first step 
                        <br>into the world of volunteering by 
                        <br>attending your very first event.</p>
                </div>
            </div>
            <div class="badge" id="badge2">
                <div class="badge-inner">
                    <img src="../Images/homepage/silver.png" alt="Badge 2">
                    <h2>Community Helper</h2>
                    <p class="badge-description">You’re making a difference! 
                    <br>Earn this badge for actively 
                    <br>participating in multiple community 
                    <br>events, showcasing your dedication 
                    <br>to helping others.</p>
                </div>
            </div>
            <div class="badge" id="badge3">
                <div class="badge-inner">
                    <img src="../Images/homepage/gold.png" alt="Badge 3">
                    <h2>Event Leader</h2>
                    <p class="badge-description">Lead the way! <br>
                        This badge is awarded for taking initiative 
                        <br>and organizing your own event, inspiring 
                        <br>others to join you in making a lasting 
                        <br>impact in your community.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div class="how-it-works">
        <h1>How It Works</h1>
        <p>We’ve made it easy for you to start volunteering.</p>
        <div class="steps-container">
            <div class="step" id="step1">
                <img src="../Images/homepage/sign_up.png" alt="Sign Up Icon">
                <h2>Sign Up</h2>
                <p>Create an account 
                <br> to start volunteering and
                    <br>access all our features.</p>
            </div>
            <div class="step" id="step2">
                <img src="../Images/homepage/find_events.png" alt="Find Events Icon">
                <h2>Find Events</h2>
                <p>Explore a variety of events 
                <br>that match your interests 
                <br>and availability.</p>
            </div>
            <div class="step" id="step3">
                <img src="../Images/homepage/contribute.png" alt="Contribute Icon">
                <h2>Contribute</h2>
                <p>Join events, 
                    <br> make a difference, 
                    <br> and track your impact.</p>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div class="testimonials">
        <h1>What Our Users Are Saying</h1>
        <p>Our platform has touched the lives of many volunteers.</p>
        <div class="testimonial-slider">
            <div class="testimonial">
                <p>"This platform helped me find meaningful volunteering opportunities."</p>
                <span>- Noor Alisa bt Anuar</span>
            </div>
            <div class="testimonial">
                <p>"I loved participating in community events, thanks to this platform!"</p>
                <span>- Tan Boe Yan</span>
            </div>
            <div class="testimonial">
                <p>"A great way to give back and meet like-minded people."</p>
                <span>- Priya Annadurai</span>
            </div>
        </div>
    </div>

    <!-- JavaScript for animation-->
    <script src=../js/homescript.js ></script>
    <!--back to top button-->
    <a href="#" id="back-to-top" title="Back to Top">&uarr;</a>
    <script src="../js/script.js"></script>
    <script src="../js/chatbotscript.js"></script>
    <!-- Footer Section -->
    <?php include('footer.php'); ?>
    
</body>
</html>