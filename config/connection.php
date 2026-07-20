<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/env.php';
$conn = new mysqli(
  env('DB_HOST'),
  env('DB_USER'),
  env('DB_PASS'),
  env('DB_NAME')
);
if ($conn->connect_error) {
  error_log("DB Connection failed: " . $conn->connect_error);
  die("Service temporarily unavailable.");
}
?>