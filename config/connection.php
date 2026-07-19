<?php
// Start session securely
if (session_status() === PHP_SESSION_NONE) {
    // Configure session cookie params for security before starting session
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 1 : 0);
    ini_set('session.cookie_samesite', 'Strict');
    ini_set('session.use_strict_mode', 1);
    session_start();
}

// Define Base Path & Base URL
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

// Load Composer Autoloader
$autoloader = BASE_PATH . '/vendor/autoload.php';
if (file_exists($autoloader)) {
    require_once $autoloader;
} else {
    die("Autoloader not found. Please run 'composer install'.");
}

// Load Environment Variables (.env)
if (file_exists(dirname(__DIR__) . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();
}

// Include Error Handler
require_once __DIR__ . '/error_handler.php';

// Include Security Helpers
require_once __DIR__ . '/security.php';

// Database config
$servername = $_ENV['DB_HOST'] ?? 'localhost';
$port       = (int)($_ENV['DB_PORT'] ?? 3306);
$username   = $_ENV['DB_USER'] ?? 'root';
$password   = $_ENV['DB_PASS'] ?? '';
$dbname     = $_ENV['DB_NAME'] ?? 'major_project';

// Create connection
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = new mysqli($servername, $username, $password, $dbname, $port);
} catch (mysqli_sql_exception $e) {
    error_log("Database connection failure: " . $e->getMessage());
    die("A database connection error occurred. Please try again later.");
}

// Perform CSRF protection for all POST requests automatically
csrf_verify();
?>