<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participant List</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/p_list_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/purecss@2.0.6/build/pure-min.css">
</head>
<body>
    <!-- Header Section -->
    <?php include('header.php'); ?>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-text-box">
            <h1>Registered Participants</h1>
            <p>Manage and track all participants for your events.</p>
        </div>
    </section>
    <br>
    <div class="container">    
    <table class="event-table">
            <thead>
                <tr>
                    <th>Event ID</th>
                    <th>User ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Joined At</th>
                    <th>Attendance</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <br>
        <div class="btn-container">
            <button id="markAttendanceBtn">Save Attendance</button>
            <button id="exportBtn">Export</button>
        </div>
        <br>
        <div class="pagination">
            <button id="prev-btn" disabled>Previous</button>
            <span id="current-page">Page 1 of 10</span>
            <button id="next-btn">Next</button>
        </div>
    </div>
    <br>
    <script src="../js/participant_listscript.js"></script>
    <!-- Footer Section -->
    <?php include('footer.php'); ?>
</body>
</html>
