<?php
include 'session_check.php';
check_if_organizer();
include '../config/connection.php';
$page = 1; 
$totalPages = 1; 

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Page</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap"> <!-- Poppins Font -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
    <link rel="stylesheet" href="../css/styles.css"> <!-- Common CSS -->
    <link rel="stylesheet" href="../css/events_styles.css"> <!-- Custom CSS for events -->
    <script src="../js/events.js" defer></script>
</head>
<body>

    <!-- Header Section -->
    <?php include('header.php'); ?>

    <!-- Hero Section -->
    <div class="hero-section">
        <h1>Find Events that Make an Impact</h1>
        <p>Join a community-driven event to make a difference today.</p>
    </div>

    <!-- Search Bar Section -->
    <div class="search-bar">
        <div class="search-input-wrapper">
            <label for="search-input" class="search-label">Search by Keywords</label>
            <div class="input-group">
                <input type="text" id="search-input" placeholder="Hinted search text">
                <button class="filter-button" onclick="loadEvents()"><i class="fas fa-search"></i></button>
            </div>
        </div>

        <!-- Dropdown for number of items per page -->
        <div class="results-and-pagination">
            <div class="results-per-page">
            <label for="results">Results Per Page:</label>
                <select name="results" id="results" onchange="updateResultsPerPage()">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
            </div>

            <!-- Pagination Control -->
            <div class="pagination">
                <button class="pagination-button" onclick="prevPage()" id="prev-btn" disabled>
                    <i class="fas fa-chevron-left"></i> Previous
                </button>
                <span id="current-page">Page 1 of 10</span>
                <button class="pagination-button" onclick="nextPage()" id="next-btn">
                    Next <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Events Section -->
    <div class="events" id="events-container">

    </div>

    <br>
    <!-- Pagination Control -->
    <div class="pagination">
        <?php if($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>&results=<?php echo $resultsPerPage; ?>" class="pagination-button">
                <i class="fas fa-chevron-left"></i> Previous
            </a>
        <?php endif; ?>

        <span id="current-page">Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>

        <?php if($page < $totalPages): ?>
            <a href="?page=<?php echo $page + 1; ?>&results=<?php echo $resultsPerPage; ?>" class="pagination-button">
                Next <i class="fas fa-chevron-right"></i>
            </a>
        <?php endif; ?>
    </div>
    <br>

    <!--back to top button-->
    <a href="#" id="back-to-top" title="Back to Top">&uarr;</a>
    <script src="../js/script.js"></script>
    <!-- Footer Section -->
    <?php include('footer.php'); ?>

    <!-- JavaScript for handling pagination and filters -->


</body>
</html>
