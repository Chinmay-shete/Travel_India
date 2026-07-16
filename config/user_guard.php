<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['email'])) {
    // Redirect to login page
    // Note: book_files is nested, so let's use absolute path redirect or relative back to root
    header("Location: ../index.php");
    exit;
}
?>
