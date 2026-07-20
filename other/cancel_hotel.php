<?php
include '../config/connection.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];
    $user_id = $_SESSION['user_Id'] ?? null;

    if (!$user_id) {
        echo "Unauthorized access.";
        exit();
    }

    // Update booking status to 'Cancelled'
    $query = "UPDATE hotel_booking SET Status='Cancelled' WHERE id = ? AND user_Id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $booking_id, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        // Either booking doesn't exist OR doesn't belong to this user
        echo "Unauthorized or booking not found.";
        exit();
    }

    echo "<script>
        alert('Hotel Booking cancel successfully!');
        window.location.href='Book_data.php'; // Redirect to bookings page
    </script>";

    $stmt->close();
    $conn->close();
}
?>
