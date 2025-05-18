<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- header.php -->
<header class="dashboard-header bg-light p-3">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center logo-container">
            <?php if ($_SESSION['role'] === 'Admin'): ?>
                    <a href="admin_dashboard.php">
                        <img src="../images/logo-new.png" alt="SphereConnect Logo" class="logo" loading="lazy">
                    </a>
                <?php endif; ?>
            <h2>SPHERECONNECT</h2>
        
        <nav>
            <ul class="nav">
                <?php if (isset($_SESSION['user_id']) && isset($_SESSION['role'])): ?>

                    <?php if ($_SESSION['role'] === 'Admin'): ?>
                        <li class="nav-item"><a href="logout.php" class="nav-link text-black"><i class="fas fa-sign-out-alt"></i> Logout</a></li>

                    <?php endif; ?>
                <?php else: ?>
    
                <?php endif; ?>
            </ul>
        </nav>
        </div>
    </div>
</header>