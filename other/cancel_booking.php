<?php
include '../config/connection.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];

    // Update booking status to 'Cancelled'
    $query = "UPDATE booking SET Status='Cancelled' WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $booking_id);

    if ($stmt->execute()) {
        echo "<script>
            alert('Booking cancel successfully!');
            window.location.href='Book_data.php'; // Redirect to bookings page
        </script>";
    } else {
        echo "<script>
            alert('Failed to cancel booking. Try again.');
            window.location.href='Book_data.php';
        </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
