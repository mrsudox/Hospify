<?php
include 'config.php'; // Include your database connection file

// Check if room_id is provided in the URL
if (isset($_GET['room_id'])) {
    $room_id = $_GET['room_id'];

    // Fetch room details from the database
    $query = "SELECT * FROM rooms WHERE room_id = $room_id";
    $result = mysqli_query($conn, $query);
    $room = mysqli_fetch_assoc($result);

    // Check if the form is submitted for updating room details
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Collect updated form data
        $room_number = $_POST['room_number'];
        $room_type = $_POST['room_type'];
        $capacity = $_POST['capacity'];
        $rate = $_POST['rate'];
        $description = $_POST['description'];

        // Update data in the rooms table
        $update_query = "UPDATE rooms SET room_number='$room_number', room_type='$room_type', capacity='$capacity', rate='$rate', description='$description' WHERE room_id = $room_id";
        mysqli_query($conn, $update_query);

        // Redirect to rooms.php after updating the room
        header("Location: rooms.php");
        exit();
    }
} else {
    // Redirect to rooms.php if room_id is not provided
    header("Location: rooms.php");
    exit();
}

?>

<?php include 'header.php'; ?>

<div class="row">
    <div class="col-md-6">
        <h2>Edit Room</h2>
        <!-- Form for editing room details -->
        <form action="rooms_edit.php?room_id=<?= $room_id; ?>" method="post">
            <div class="form-group">
                <label for="room_number">Room Number:</label>
                <input type="text" class="form-control" name="room_number" value="<?= $room['room_number']; ?>" required>
            </div>
            <div class="form-group">
                <label for="room_type">Room Type:</label>
                <input type="text" class="form-control" name="room_type" value="<?= $room['room_type']; ?>" required>
            </div>
            <div class="form-group">
                <label for="capacity">Capacity:</label>
                <input type="number" class="form-control" name="capacity" value="<?= $room['capacity']; ?>" required>
            </div>
            <div class="form-group">
                <label for="rate">Rate:</label>
                <input type="text" class="form-control" name="rate" value="<?= $room['rate']; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" name="description" rows="4"><?= $room['description']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Room</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
