<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'professional_bookstore');

// Site Configuration
define('SITE_NAME', 'Pendulum Books');
define('SITE_URL', 'http://localhost/professional-bookstore');
define('SITE_EMAIL', 'info@pendulumbooks.com');
define('CURRENCY', '$');

// Path Configuration
define('BASE_PATH', dirname(__DIR__));
define('ASSETS_PATH', SITE_URL . '/assets');
define('UPLOADS_PATH', BASE_PATH . '/uploads');

// Display Errors (Turn off in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Timezone
date_default_timezone_set('America/New_York');

// Include database connection
require_once 'database.php';
?>