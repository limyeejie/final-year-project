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

// Generate HTML for the sidebar based on preferences
ob_start();
?>

<!-- Sidebar HTML -->
<ul class="side-menu top">
    <li class="active">
        <a href="#" data-section="dashboard">
            <span class="text">Dashboard</span>
        </a>
    </li>
    <!-- Only students have My Rewards and Notifications section -->
    <?php if (isset($user['role']) && $user['role'] == 'Student'): ?>
        <li>
            <a href="#" data-section="my-rewards">
                <span class="text">My Rewards</span>
            </a>
        </li>
        <?php if (isset($profiles['alert']) && $profiles['alert'] == '1'): ?>
            <li>
                <a href="#" data-section="notifications">
                    <span class="text">Notifications</span>
                </a>
            </li>
        <?php endif; ?>
    <?php endif; ?>
    <li>
        <a href="#" data-section="settings">
            <span class="text">Settings</span>
        </a>
    </li>
</ul>

<?php
$html = ob_get_clean();
echo $html;
?>
