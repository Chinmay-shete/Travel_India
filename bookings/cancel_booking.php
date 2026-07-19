<?php
include '../config/connection.php'; // Include your database connection

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];

    $user_id = $_SESSION['user_id'] ?? '';
    if (empty($user_id)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Unauthorized access.']);
        exit();
    }

    // Update booking status to 'Cancelled' only if it belongs to this user
    $query = "UPDATE booking SET Status='Cancelled' WHERE id=? AND user_Id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $booking_id, $user_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Booking not found or already cancelled.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to cancel booking.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
}
?>
