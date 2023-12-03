<?php
include 'config.php'; // Include your database connection file

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $room_number = $_POST['room_number'];
    $room_type = $_POST['room_type'];
    $capacity = $_POST['capacity'];
    $rate = $_POST['rate'];
    $description = $_POST['description'];

    // Insert data into the rooms table
    $query = "INSERT INTO rooms (room_number, room_type, capacity, rate, description) VALUES ('$room_number', '$room_type', '$capacity', '$rate', '$description')";
    mysqli_query($conn, $query);

    // Redirect to rooms.php after adding a new room
    header("Location: rooms.php");
    exit();
}

?>

<?php include 'header.php'; ?>

<div class="row">
    <div class="col-md-6">
        <h2>Add New Room</h2>
        <!-- Form for adding new rooms -->
        <form action="rooms_add.php" method="post">
            <div class="form-group">
                <label for="room_number">Room Number:</label>
                <input type="text" class="form-control" name="room_number" required>
            </div>
            <div class="form-group">
                <label for="room_type">Room Type:</label>
                <input type="text" class="form-control" name="room_type" required>
            </div>
            <div class="form-group">
                <label for="capacity">Capacity:</label>
                <input type="number" class="form-control" name="capacity" required>
            </div>
            <div class="form-group">
                <label for="rate">Rate:</label>
                <input type="text" class="form-control" name="rate" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" name="description" rows="4"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Room</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
