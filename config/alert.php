<?php
if (count(get_included_files()) === 1) {
    http_response_code(403);
    exit("Direct access not allowed");
}

if (isset($_SESSION['error'])) {
    echo h($_SESSION['error']);
    unset($_SESSION['error']);
}

if (isset($_SESSION['status'])) {
    echo "<h4>" . h($_SESSION['status']) . "</h4>";
    unset($_SESSION['status']);
}
?>