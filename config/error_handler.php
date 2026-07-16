<?php
// Prevent direct access
if (count(get_included_files()) === 1) {
    http_response_code(403);
    exit("Direct access not allowed");
}

// Ensure logs directory exists
$logDir = dirname(__DIR__) . '/logs';
if (!file_exists($logDir)) {
    mkdir($logDir, 0755, true);
}

// Error reporting settings based on environment
$appEnv = $_ENV['APP_ENV'] ?? 'production';
$appDebug = filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN);

if ($appEnv === 'development' || $appDebug) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
}

ini_set('log_errors', 1);
ini_set('error_log', $logDir . '/error.log');

// Custom error handler
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    $message = "[" . date('Y-m-d H:i:s') . "] Error ($errno): $errstr in $errfile on line $errline\n";
    error_log($message, 3, ini_get('error_log'));
    
    // In dev, print the error; in prod, show a generic message if it is a fatal type
    $appEnv = $_ENV['APP_ENV'] ?? 'production';
    $appDebug = filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN);
    
    if ($appEnv === 'development' || $appDebug) {
        return false; // Let default PHP handler run
    }
    
    // For critical errors in production, end execution gracefully
    if (in_array($errno, [E_USER_ERROR, E_RECOVERABLE_ERROR])) {
        http_response_code(500);
        exit("A system error occurred. Please try again later.");
    }
    
    return true; // Don't execute PHP internal error handler
}

set_error_handler("customErrorHandler");

// Custom exception handler
function customExceptionHandler($exception) {
    $message = "[" . date('Y-m-d H:i:s') . "] Uncaught Exception: " . $exception->getMessage() . 
               " in " . $exception->getFile() . " on line " . $exception->getLine() . "\n" .
               $exception->getTraceAsString() . "\n";
    error_log($message, 3, ini_get('error_log'));
    
    $appEnv = $_ENV['APP_ENV'] ?? 'production';
    $appDebug = filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN);
    
    if ($appEnv === 'development' || $appDebug) {
        echo "<h2>Uncaught Exception</h2>";
        echo "<p><strong>Message:</strong> " . h($exception->getMessage()) . "</p>";
        echo "<p><strong>File:</strong> " . h($exception->getFile()) . " on line " . h($exception->getLine()) . "</p>";
        echo "<pre>" . h($exception->getTraceAsString()) . "</pre>";
    } else {
        http_response_code(500);
        exit("A system error occurred. Please try again later.");
    }
}

set_exception_handler("customExceptionHandler");
?>
