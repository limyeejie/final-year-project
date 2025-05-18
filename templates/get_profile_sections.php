<?php
include '../config/connection.php';

// Fetch user preferences from the database
if (isset($user['role']) && $user['role'] == 'Student') {
    $userId = $_SESSION['user_id']; // Ensure this is correctly set
    $stmt = $conn->prepare("SELECT recommend, alert, news FROM profiles WHERE userId = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $profiles = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
}

// Generate HTML for the profile sections based on preferences
ob_start();
?>

<!-- Sections HTML -->
<!-- DASHBOARD SECTION -->
<div class="section dashboard" style="display: block;">
    <p class="content-title">Events</p>
    <div class="data-insight">
        <!-- Data Overview total event -->
        <?php if ($user['role'] == 'Student') : ?>
            <div class="data-total">
                <span class="material-symbols-outlined">event_list</span>
                <div class="middle">
                    <div class="left">
                        <h3>Total Events Attended:</h3>
                        <h1 id="totalAttended"></h1>

                        <!-- New Sections -->
                        <h3>Events Volunteered:</h3>
                        <h1 id="totalVolunteered"></h1>

                        <h3>Events Participated:</h3>
                        <h1 id="totalParticipated"></h1>
                    </div>
                    <!-- <div class="progress"></div> -->
                </div>
            </div>
        <?php elseif ($user['role'] == 'Organizer') : ?>
            <div class="data-total">
                <span class="material-symbols-outlined">event_list</span>
                <div class="middle">
                    <div class="left">
                        <h3>Total Events:</h3>
                        <h1 id="totalEvents"></h1>

                        <!-- New Sections -->
                        <h3>Total Volunteering Events:</h3>
                        <h1 id="totalVolunteeringEvents"></h1>

                        <h3>Total Participated:</h3>
                        <h1 id="totalParticipated"></h1>

                        <h3>Total Volunteered:</h3>
                        <h1 id="totalVolunteered"></h1>
                    </div>
                    <!-- <div class="progress"></div> -->
                </div>
            </div>
        <?php endif; ?>
        <div class="data-upcoming">
            <h3>Upcoming Events:</h3>
            <div class="event-counts">
                <div class="event-types">
                    <div class="event-type skills">
                        <span class="material-symbols-outlined" style="background: #faaca8;">volunteer_activism</span>
                        <h3>Skills Sharing Session:&nbsp;<p id="skill-count"></p>
                        </h3>
                    </div>
                    <div class="event-type clean">
                        <span class="material-symbols-outlined">event</span>
                        <h3>Community-Clean Up:&nbsp;<p id="clean-count"></p>
                        </h3>
                    </div>
                    <div class="event-type charity">
                        <span class="material-symbols-outlined">event</span>
                        <h3>Charity Event:&nbsp;<p id="charity-count"></p>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="total-events">
                <h1>Total Events: &nbsp;<p id="total-upcoming"></p>
                </h1>
            </div>
        </div>
    </div>

    <!-- Upcoming Event -->
    <?php if ($user['role'] == 'Student') : ?>
        <h2>Upcoming Event</h2>
        <div class="upcoming-event-evt">
            <div id="upcomingEvtContainer" class="card-list">

            </div>
        </div>
        <!-- Event Detail Modal -->
        <div class="event-detail-modal" id="eventDetailModal" style="display: none;">
            <div class="modal-upcoming-content">
                <span class="close-modal" id="closeUpcomingModal">&times;</span>
                <h3 id="upcomingEventName"></h3>
                <p id="upcomingEventLocation"></p>
                <p id="upcomingEventDate"></p>
                <p id="upcomingEventOrganizerName"></p>
                <p id="upcomingEventOrganizerContact"></p>
                <p id="upcomingEventOrganizerNumber"></p>
                <p id="upcomingMaxParticipants"></p>
            </div>
        </div>
    <?php elseif ($user['role'] == 'Organizer') : ?>
        <h2>Upcoming Event Overview</h2>
        <div class="upcoming-event-evt">
            <div id="evtOverviewContainer" class="card-list">

            </div>
        </div>
        <!-- Event Detail Modal -->
        <div class="event-detail-modal" id="eventDetailModal" style="display: none;">
            <div class="modal-upcoming-content">
                <span class="close-modal" id="closeUpcomingModal">&times;</span>
                <h3 id="upcomingEventName"></h3>
                <p id="upcomingEventLocation"></p>
                <p id="upcomingEventDate"></p>
                <p id="upcomingEventOrganizerContact"></p>
                <p id="upcomingMaxParticipants"></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- past event review and feedback container -->
    <!-- Check user role -->
    <?php if ($user['role'] == 'Student') : ?>
        <!-- Past Event Review for Students -->
        <?php if (isset($profiles['news']) && $profiles['news'] == '1'): ?>
            <h2>Past Event Review</h2>
            <div class="past-event-review-evt">
                <div id="pastEvtContainer" class="card-list">
                    <!-- Event review items will be dynamically populated here -->
                </div>
            </div>
        <?php endif; ?>
    <?php elseif ($user['role'] == 'Organizer') : ?>
        <!-- Feedbacks container for Organizers -->
        <h2>Feedbacks</h2>
        <div class="feedbacks-container">
            <div id="feedbacksList" class="card-list">
                <!-- Feedback items will be dynamically populated here -->
            </div>
        </div>

    <?php endif; ?>
    <!-- Feedback form -->
    <div class="wrapper" id="feedbackModal">
        <div class="wrapper-content">
            <span class="close" id="closeModal">&times;</span>
            <h3 class="event-name">Event Feedback</h3>
            <input type="hidden" class="event-id" value="">
            <div class="rating">
                <input type="number" name="rating" hidden>
                <i class='bx bx-star star' style="--i: 0;"></i>
                <i class='bx bx-star star' style="--i: 1;"></i>
                <i class='bx bx-star star' style="--i: 2;"></i>
                <i class='bx bx-star star' style="--i: 3;"></i>
                <i class='bx bx-star star' style="--i: 4;"></i>
            </div>
            <textarea name="comment" id="comment" cols="15" rows="5" placeholder="Enter your opinions here..."></textarea>
            <div class="btn-group">
                <button type="submit" class="btn submit">Send</button>
                <button type="reset" class="btn cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- MY REWARDS SECTION -->
<?php if (isset($user['role']) && $user['role'] == 'Student'): ?>
    <div class="section my-rewards" style="display: none;">
        <p class="content-title">My Rewards</p>

        <!-- Display Achieved Reward -->
        <div class="rewards-section">
            <h2>Your Achievements</h2>

            <!-- Set when the user achieved then only can download -->
            <!-- Display badges based on earned badges array -->
            <div class="badges-container">
                <?php if (in_array('first-event', $earnedBadges)): ?>
                    <div class="badge" id="first-event">
                        <h3>First Activity</h3>
                        <p>Awarded for completing your first activity (event or volunteer).</p>
                        <button class="download-btn">Download Badge</button>
                    </div>
                <?php endif; ?>

                <?php if (in_array('community-helper', $earnedBadges)): ?>
                    <div class="badge" id="community-helper">
                        <h3>Community Helper</h3>
                        <p>Earned for participating in multiple activities.</p>
                        <button class="download-btn">Download Badge</button>
                    </div>
                <?php endif; ?>

                <?php if (in_array('event-leader', $earnedBadges)): ?>
                    <div class="badge" id="event-leader">
                        <h3>Event Leader</h3>
                        <p>Earned by organizing multiple events.</p>
                        <button class="download-btn">Download Badge</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recommended activity -->
        <?php if (isset($profiles['recommend']) && $profiles['recommend'] == '1'): ?>
            <div class="recommended-activities">
                <h2>Recommended Activities for Next Badge</h2>
                <!-- Maybe can redirect to the event & volunteer pages -->
                <div class="activities-container" id="activity-list">
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<!-- NOTIFICATIONS SECTION -->
<?php if (isset($user['role']) && $user['role'] == 'Student'): ?>
    <?php if (isset($profiles['alert']) && $profiles['alert'] == '1'): ?>
        <div class="section notifications" style="display: none;">
            <div class="notificationContainer">
                <div class="header">
                    <div class="notificationHeader">
                        <h2>Notifications</h2>
                        <span id="num-of-notif"></span>
                    </div>
                    <p id="mark-as-read">Mark All As Read</p>
                </div>
                <div class="main">

                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php
$html = ob_get_clean();
echo $html;
?>