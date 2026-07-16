<?php
// Prevent direct access
if (count(get_included_files()) === 1) {
    http_response_code(403);
    exit("Direct access not allowed");
}

/**
 * Enqueues an email to be sent asynchronously by the background worker.
 *
 * @param mysqli $conn
 * @param string $to
 * @param string $subject
 * @param string $body
 * @return bool
 */
function enqueue_email($conn, $to, $subject, $body) {
    $stmt = $conn->prepare("INSERT INTO email_queue (to_email, subject, body, status) VALUES (?, ?, ?, 'pending')");
    if ($stmt) {
        $stmt->bind_param("sss", $to, $subject, $body);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    return false;
}
?>
