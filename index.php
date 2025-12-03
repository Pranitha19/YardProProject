<?php

session_start();
//base paths
define('BASE_PATH', __DIR__);
define('CONTROLLERS_PATH', BASE_PATH . '/Controllers');
define('VIEWS_PATH', BASE_PATH . '/Views');
define('MODELS_PATH', BASE_PATH . '/Models');

//required files
require_once BASE_PATH . '/config/pdo.php';
require_once BASE_PATH . '/helpers/flash.php';
require_once CONTROLLERS_PATH . '/UserController.php';
require_once CONTROLLERS_PATH . '/AdminController.php';
require_once CONTROLLERS_PATH . '/EmployeeController.php';

// Get the requested route from URL or POST data
$request = isset($_GET['route']) ? trim($_GET['route'], '/') : (isset($_POST['route']) ? trim($_POST['route'], '/') : '');

// Split route into parts (e.g., "user/home" → ['user', 'home'])
$routeParts = array_filter(explode('/', $request));

// Determine user type and page
$userType = isset($routeParts[0]) ? $routeParts[0] : null;
$page = isset($routeParts[1]) ? $routeParts[1] : null;

// Check authentication status
$isAuthenticated = isset($_SESSION['user_id']);
$userId = $_SESSION['user_id'] ?? null;

//user routes
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

    if ($page === 'home' || $page === null) {
        include VIEWS_PATH . '/user/home.php';
        exit;
    }

    if ($page === 'book-service') {
        include VIEWS_PATH . '/user/bookService.php';
        exit;
    }

    if ($page === 'view-bookings') {
        include VIEWS_PATH . '/user/viewBookings.php';
        exit;
    }

    if ($page === 'edit-booking') {
        include VIEWS_PATH . '/user/editBooking.php';
        exit;
    }

    if ($page === 'edit-profile') {
        include VIEWS_PATH . '/user/editProfile.php';
        exit;
    }

    if ($page === 'fetch-slots') {
        include VIEWS_PATH . '/user/fetch_slots.php';
        exit;
    }
}

//Admin routes
// Route: Admin Login page (accessible without authentication)
if ($userType === 'admin' && $page === 'login') {
    include VIEWS_PATH . '/admin/login.php';
    exit;
}

// Route: Admin Logout (requires authentication)
if ($userType === 'admin' && $page === 'logout') {
    if (isset($_SESSION['admin_id'])) {
    include VIEWS_PATH. '/admin/logout.php';
        exit;
    }
}

// Protected Admin Routes - require authentication
if ($userType === 'admin') {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: /YardProProject/?route=admin/login&msg=Please+login');
        exit;
    }

    if ($page === 'home' || $page === null) {
        include VIEWS_PATH . '/admin/home.php';
        exit;
    }

    if ($page === 'view-bookings') {
        include VIEWS_PATH . '/admin/viewAllBookings.php';
        exit;
    }

    if ($page === 'update-booking') {
        include VIEWS_PATH . '/admin/updateBooking.php';
        exit;
    }

    if ($page === 'add-service-center') {
        include VIEWS_PATH . '/admin/addServiceCenter.php';
        exit;
    }
    if ($page === 'edit-service-center') {
        include VIEWS_PATH . '/admin/editServiceCenter.php';
        exit;
    }

    if ($page === 'delete-service-center') {
        include VIEWS_PATH . '/admin/deleteServiceCenter.php';
        exit;
    }

    if ($page === 'register-employee') {
        include VIEWS_PATH . '/admin/registerEmployee.php';
        exit;
    }
}

//Employee routes
// Route: Employee Login page (accessible without authentication)
if ($userType === 'employee' && $page === 'login') {
    include VIEWS_PATH . '/employee/login.php';
    exit;
}

// Route: Employee Logout (requires authentication)
if ($userType === 'employee' && $page === 'logout') {
    if (isset($_SESSION['employee_id'])) {
    include VIEWS_PATH. '/employee/logout.php';
        exit;
    }
}

// Protected Employee Routes - require authentication
if ($userType === 'employee') {
    if (!isset($_SESSION['employee_id'])) {
        header('Location: /YardProProject/?route=employee/login&msg=Please+login');
        exit;
    }

    if ($page === 'home' || $page === null) {
        include VIEWS_PATH . '/employee/home.php';
        exit;
    }

    if ($page === 'view-requests') {
        include VIEWS_PATH . '/employee/viewRequest.php';
        exit;
    }

    if ($page === 'update-status') {
        include VIEWS_PATH . '/employee/updateStatus.php';
        exit;
    }

    if ($page === 'edit-profile') {
        include VIEWS_PATH . '/employee/editProfile.php';
        exit;
    }
}


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

//page not found

// If no route matched, show 404 error
if (file_exists(VIEWS_PATH . '/errors/404.php')) {
    include VIEWS_PATH . '/errors/404.php';
} else {
    http_response_code(404);
    echo "<h1>404 - Page Not Found</h1>";
}
?>
