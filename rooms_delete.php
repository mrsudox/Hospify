<?php
include 'config.php'; // Include your database connection file

// Check if room_id is provided in the URL
if (isset($_GET['room_id'])) {
    $room_id = $_GET['room_id'];

    // Delete room from the database
    $delete_query = "DELETE FROM rooms WHERE room_id = $room_id";
    mysqli_query($conn, $delete_query);

    // Redirect to rooms.php after deleting the room
    header("Location: rooms.php");
    exit();
} else {
    // Redirect to rooms.php if room_id is not provided
    header("Location: rooms.php");
    exit();
}
?>
