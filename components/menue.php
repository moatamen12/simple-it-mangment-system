<?php
include_once("../Connection/connection.php");

// Check if user is logged in and user type is set
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// Get user type from session
$user_type = $_SESSION['role'];

// Define display names for each user type
$user_dashboards = [
    'Agent' => [
        'page' => 'Agent.php',
        'title' => 'Agent '
    ],
    'Stock Manager' => [
        'page' => 'STOK-MAniger.php',
        'title' => 'Stock Manager '
    ],
    'Administrator' => [
        'page' => 'Administrator.php',
        'title' => 'Administrator '
    ]
];

// Get the appropriate dashboard page and title
$dashboard_page = isset($user_dashboards[$user_type]) ? $user_dashboards[$user_type]['page'] : 'index.php';
$page_title = isset($user_dashboards[$user_type]) ? $user_dashboards[$user_type]['title'] : 'Dashboard';


?>
 <!-- Sidebar Navigation -->
 <div class="sidebar">
    <div class="logo">
        <img src="images/logo.png" alt="Logo">
    </div>
    <nav>
        <ul>
            <li>
                <a href="inventory-Equipment.php" class="nav-link">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="index.php" class="nav-link">
                    <i class="fas fa-user"></i>
                    <span>Profile</span>
                </a>
            </li>
            <li>
                <a href="<?php echo $dashboard_page; ?>" class="nav-link">
                    <i class="fas fa-boxes"></i>
                    <span><?php echo $page_title; ?></span>
                </a>
            </li>
            <li class="dropdown">
                <a href="#" class="nav-link">
                    <i class="fas fa-tools"></i>
                    <span>Requests</span>
                    <i class="fas fa-chevron-down"></i>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="ConReq.php" class="nav-link">
                            <i class="fas fa-box"></i>
                            <span>Consumable Requests</span>
                        </a>
                    </li>
                    <li>
                        <a href="RepReq.php" class="nav-link">
                            <i class="fas fa-wrench"></i>
                            <span>Repair Requests</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="logout">
                <a href="login.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>
</div>