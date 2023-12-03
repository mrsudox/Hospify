<?php
include 'config.php'; // Include your database connection file

// Check if booking_id is provided in the URL
if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    // Delete the booking from the database
    $delete_query = "DELETE FROM bookings WHERE booking_id = $booking_id";
    $conn->query($delete_query);
}

// Redirect to bookings.php after deleting the booking
header("Location: bookings.php");
exit();
?>
