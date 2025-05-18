<?php
// Include database connection
include '../config/connection.php';

// Get the volunteer ID from the URL query parameter
$volunteerId = isset($_GET['volunteerId']) ? (int)$_GET['volunteerId'] : 1; // Default to 1 if no ID is provided

// Prepare and execute the query to fetch volunteer opportunity details
$query = "SELECT * FROM volunteers WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $volunteerId);
$stmt->execute();
$result = $stmt->get_result();

// Check if a volunteer opportunity was found
if ($result->num_rows > 0) {
    $volunteer = $result->fetch_assoc();
} else {
    // Handle the case where the event does not exist
    $volunteer = null;
}

// Close the statement
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>See Opportunities</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/opportunity_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/purecss@2.0.6/build/pure-min.css">
</head>
<body>
    <!-- Header Section -->
    <?php include('header.php'); ?>

    <!-- Volunteer Opportunities -->
    <div class="volunteer-opportunities">
        <h1>Volunteer Opportunities</h1>
        <div id="opportunity-container" class="opportunity-container">
            <?php if ($volunteer): ?>
                <!--<div class="opportunity-card">-->
                    <div class="opportunity-img-sec">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($volunteer['image']); ?>"
                            alt="Volunteer Image">
                    </div>
                    <div class="opportunity-details">
                        <h2><?php echo htmlspecialchars($volunteer['title']); ?></h2>
                        <p>Description: <?php echo htmlspecialchars($volunteer['description']); ?></p>
                        <p>Date: <?php echo htmlspecialchars($volunteer['date']); ?></p>
                        <p>Time: <?php echo htmlspecialchars($volunteer['event_time']); ?></p>
                        <p>Location: <?php echo htmlspecialchars($volunteer['location']); ?></p>
                        <p>Organizer Name: <?php echo htmlspecialchars($volunteer['organizer_name']); ?></p>
                        <p>Organizer Contact (Email): <?php echo htmlspecialchars($volunteer['organizer_contact']); ?></p>
                        <p>Organizer Contact (Phone): <?php echo htmlspecialchars($volunteer['organizer_number']); ?></p>
                        <p>Requirements: <?php echo htmlspecialchars($volunteer['requirement']); ?></p>
                        <button class="back-btn" onclick="history.back()">Go Back</button>
                        <button class="apply-btn" data-volunteer-id="<?php echo $volunteerId; ?>">Apply Now</button>
                    </div>
                <!--</div>-->
            <?php else: ?>
                <p>No volunteer opportunity found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Apply Form -->
    <div id="applyFormModal" class="modal">
        <div class="form-content">
            <span class="close-btn">&times;</span>
            <h2>Apply for Volunteer Opportunity</h2>
            <?php if ($volunteer): ?>
                    <form action="application_volunteer.php" method="POST" style="display: inline;">
                        <input type="hidden" name="volunteerId" value="<?php echo $volunteerId; ?>">
                     <button type="submit" class="submit-btn">Join Now</button>
                    </form>
            <?php else: ?>
                <h1>Volunteer Opportunity Not Found</h1>
                <p>Sorry, the volunteer opportunity you are looking for does not exist.</p>
            <?php endif; ?>

        </div>
    </div>


        </div>
    </div>
    <!--back to top button-->
    <a href="#" id="back-to-top" title="Back to Top">&uarr;</a>
    <script src="../js/script.js"></script>
    <!-- Footer Section -->
    <?php include('footer.php'); ?>

    <script src="../js/opportunityscript.js"></script>
</body>
</html>