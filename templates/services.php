<?php
include 'session_check.php';
check_user_logged_in();
check_if_student();

?>

<!--services page-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/services_styles.css">
    <link rel="stylesheet" href="../css/chatbot_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/purecss@2.0.6/build/pure-min.css">
</head>
<body>
    <!-- Header Section -->
    <?php include('header.php'); ?>
    <!-- Chatbot Section -->
    <?php include('chatbot_response.php'); ?>

    <!--services top-->
    <div class="services-top">
        <div class="left-text side-text">
            <br> <!--spacing-->
            <h1 class="services-top-title">Organize Events,</h1>
            <h1 class="services-top-title">Connect with Volunteers,</h1>
            <h1 class="services-top-title">Make a Difference</h1>
        </div>
        <div class="scene">
         <div class="cube">
            <div class="cube__face cube__face--front">
              <img src="../images/services page/slider1.jpg" alt="Slider 1">
            </div>
            <div class="cube__face cube__face--back">
              <img src="../images/services page/slider2.jpg" alt="Slider 2">
            </div>
            <div class="cube__face cube__face--left">
              <img src="../images/services page/slider3.jpg" alt="Slider 3">
            </div>
            <div class="cube__face cube__face--right">
              <img src="../images/services page/slider4.jpg" alt="Slider 4">
            </div>
            <div class="cube__face cube__face--top">
              <img src="../images/services page/slider5.jpg" alt="Slider 5">
            </div>
            <div class="cube__face cube__face--bottom">
              <img src="../images/logo.png" alt="Slider 6">
            </div>
         </div>
        </div>
        <div class="right-text side-text">
            <p class="services-top-subtext">With SphereConnect, easily organize events,</p>
            <p class="services-top-subtext">find and connect with volunteers,</p>
            <p class="services-top-subtext">and make a lasting difference in your community.</p>
            <p class="services-top-subtext">Whether you’re hosting or participating,</p>
            <p class="services-top-subtext">our platform empowers you</p>
            <p class="services-top-subtext">to take meaningful action and contribute to positive change.</p>
        </div>
    </div>

    <!-- AI Insights Section -->
      <div class="ai-insights">
         <h1 style="text-align:center">Overall Insights</h1> 
         <div class="insights-container"> 
            <div class="insight" id="predictedVolunteersInsight">
                <h2>Predicted Volunteers for Next Month</h2>
                <p id="predictedVolunteers">Loading...</p>
            </div>
            <div class="insight" id="predictedEventsInsight">
                <h2>Predicted Popular Event Categories</h2>
                <p id="predictedEvents">Loading...</p>
            </div>
            <div class="insight" id="popularEventsInsight"> 
                <h2>Overall Event Categories</h2> 
                <canvas id="popularEventsChart" width="100" height="75"></canvas> 
            </div>
            <div class="insight" id="volunteerTrendsInsight"> 
                <h2>Overall Volunteer Trends</h2> 
                <canvas id="volunteerTrendsChart" width="100" height="75"></canvas> 
            </div> 
        </div> 
    </div>

    <!--services overview-->
    <div class="services-overview">
        <h1 style="text-align:center">Services Overview</h1>
        <div class="services-container">
            <div class="service" id="service1">
                <img src="../images/services page/event-organization.png" alt="Event Organization Icon">
                <h2>Event Organization</h2>
                <p class="services-description">Create and manage 
                    <br>impactful events 
                    <br>easily.
                </p>
            </div>
            <div class="service" id="service2">
                <img src="../images/services page/volunteer.png" alt="Volunteer Icon">
                <h2>Volunteer Coordination</h2>
                <p class="services-description">Find and organize
                    <br>volunteers for your
                    <br>events.
                </p>
            </div>
            <div class="service" id="service3">
                <img src="../images/services page/community-resources.png" alt="Community Resources Icon">
                <h2>Community Resources</h2>
                <p class="services-description">Access tools and
                    <br>materials for better
                    <br>event management.
                </p>
            </div>
        </div>
    </div>

    <!--how it works-->
    <div class="how-it-works-services">
        <h1 style="text-align:center">How it works</h1>
        <div class="steps-container-services">
            <div class="step" id="step1">
                <img src="../Images/homepage/sign_up.png" alt="Sign Up Icon">
                <h2>Sign Up and Login</h2>
                <p>Create an account 
                    <br>to start creating events
                    <br>and connect with 
                    <br>university students.
                </p>
            </div>
            <div class="step" id="step2">
                <img src="../images/services page/create-event.png" alt="Create Event Icon">
                <h2>Create or Browse Events</h2>
                <p>Create an event
                    <br>and attract collaborations.
                </p>
            </div>
            <div class="step" id="step3">
                <img src="../images/services page/manage-event.png" alt="Manage Event Icon">
                <h2>Get Involved or Manage</h2>
                <p>Manage your events
                    <br>without paying any fees.
                </p>
            </div>
            <div class="step" id="step4">
                <img src="../images/services page/positive-impact.png" alt="Positive Impact Icon">
                <h2>Make an Impact</h2>
                <p>Making a positive
                    <br>impact on your
                    <br>community.
                </p>
            </div>
        </div>
    </div>
    <!--back to top button-->
    <a href="#" id="back-to-top" title="Back to Top">&uarr;</a>
    
    <!-- Include Chart.js library --> 
     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
     <script src="../js/script.js"></script>
    <script src="../js/chatbotscript.js"></script>
    <script src="../js/services.js"></script>
    <!-- Footer Section -->
    <?php include('footer.php'); ?>
</body>
</html>