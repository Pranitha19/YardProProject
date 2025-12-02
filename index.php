<?php
/**
 * YardPro - Front Controller / Router
 * This file acts as the main entry point for the application
 * Handles routing, authentication, and request dispatching
 */

session_start();

// Define base paths
define('BASE_PATH', __DIR__);
define('CONTROLLERS_PATH', BASE_PATH . '/Controllers');
define('VIEWS_PATH', BASE_PATH . '/Views');
define('MODELS_PATH', BASE_PATH . '/Models');

// Include required files
require_once BASE_PATH . '/config/pdo.php';
require_once CONTROLLERS_PATH . '/UserController.php';

// Get the requested route from URL
$request = isset($_GET['route']) ? trim($_GET['route'], '/') : '';

// Split route into parts (e.g., "user/home" → ['user', 'home'])
$routeParts = array_filter(explode('/', $request));

// Determine user type and page
$userType = isset($routeParts[0]) ? $routeParts[0] : null;
$page = isset($routeParts[1]) ? $routeParts[1] : null;

// Check authentication status
$isAuthenticated = isset($_SESSION['user_id']);
$userId = $_SESSION['user_id'] ?? null;

/**
 * ==================== USER ROUTES ====================
 */

// Route: Login page (accessible without authentication)
if ($userType === 'user' && $page === 'login') {
    include VIEWS_PATH . '/user/login.php';
    exit;
}

// Route: Register page (accessible without authentication)
if ($userType === 'user' && $page === 'register') {
    include VIEWS_PATH . '/user/register.php';
    exit;
}

// Route: Logout (requires authentication)
if ($userType === 'user' && $page === 'logout') {
    if ($isAuthenticated) {
        session_destroy();
        header('Location: /YardProProject/?route=user/login');
        exit;
    }
}

// Protected User Routes - require authentication
if ($userType === 'user') {
    if (!$isAuthenticated) {
        header('Location: /YardProProject/?route=user/login&msg=Please+login');
        exit;
    }

    // Route: User home/dashboard
    if ($page === 'home' || $page === null) {
        include VIEWS_PATH . '/user/home.php';
        exit;
    }

    // Route: Book service
    if ($page === 'book-service') {
        include VIEWS_PATH . '/user/bookService.php';
        exit;
    }

    // Route: View bookings
    if ($page === 'view-bookings') {
        include VIEWS_PATH . '/user/viewBookings.php';
        exit;
    }

    // Route: Edit booking
    if ($page === 'edit-booking') {
        include VIEWS_PATH . '/user/editBooking.php';
        exit;
    }

    // Route: Edit profile
    if ($page === 'edit-profile') {
        include VIEWS_PATH . '/user/editProfile.php';
        exit;
    }

    // Route: Fetch slots (AJAX endpoint)
    if ($page === 'fetch-slots') {
        include VIEWS_PATH . '/user/fetch_slots.php';
        exit;
    }
}

/**
 * ==================== HOME / LANDING PAGE ====================
 */

// Default route - landing page (accessible to all)
if ($request === '' || $userType === null) {
    if ($isAuthenticated) {
        // If logged in, redirect to user dashboard
        header('Location: /YardProProject/?route=user/home');
        exit;
    }
    
    // Show landing page for unauthenticated users
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Welcome to YardPro</title>
        <link rel="stylesheet" href="/YardProProject/Static/css/landing.css">
    </head>

    <body>

        <!-- Navbar -->
        <div class="navbar">
            <div class="logo">
                <img src="/YardProProject/Static/images/lawn_logo.png" alt="YardPro" class="logo-img">
                <span class="logo-text">YardPro</span>
            </div>

            <div class="nav-buttons">
                <a href="/YardProProject/?route=user/register" class="btn">Register</a>
                <a href="/YardProProject/?route=user/login" class="btn btn-login">Login</a>
            </div>
        </div>

        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-content">
                <h1>Welcome to <span>YardPro</span></h1>
                <p>Your trusted partner for professional lawn and landscaping services.<br>
                    Book reliable experts near you, anytime.</p>
            </div>
        </section>

    </body>
    </html>
    <?php
    exit;
}

/**
 * ==================== 404 - PAGE NOT FOUND ====================
 */

// If no route matched, show 404 error
if (file_exists(VIEWS_PATH . '/errors/404.php')) {
    include VIEWS_PATH . '/errors/404.php';
} else {
    http_response_code(404);
    echo "<h1>404 - Page Not Found</h1>";
}
?>
