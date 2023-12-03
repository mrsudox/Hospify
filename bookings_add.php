<?php
include 'config.php'; // Include your database connection file



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $customer_id = $_POST['customer_id'];
    $room_id = $_POST['room_id'];
    $check_in_date = $_POST['check_in_date'];
    $check_out_date = $_POST['check_out_date'];
    $total_cost = $_POST['total_cost'];
    $discount = $_POST['discount'];
    $paid_amount = $_POST['paid_amount'];
    $male_guests = $_POST['male_guests'];
    $female_guests = $_POST['female_guests'];
    $children_guests = $_POST['children_guests'];

    // Insert data into the bookings table
    $query = "INSERT INTO bookings (customer_id, room_id, check_in_date, check_out_date, total_cost, discount, male_guests, female_guests, children_guests) VALUES ('$customer_id', '$room_id', '$check_in_date', '$check_out_date', '$total_cost', '$discount', '$male_guests', '$female_guests', '$children_guests')";
    $conn->query($query);

    // Get the booking_id of the last inserted record
    $booking_id = $conn->insert_id;

    // Insert data into the cash_in table for the payment
    $desc = "Payment added";
    $added_from = "hotel";
    $added_by = getCurrentUserInfo();

    $cashInQuery = "INSERT INTO cash_in (timestamp, value, description, added_by, added_from, alternate_id) VALUES (NOW(), '$paid_amount', '$desc', '$added_by', '$added_from', '$booking_id')";
    $conn->query($cashInQuery);

    // Redirect to bookings.php after adding a new booking and cash_in record
    header("Location: bookings.php");
    exit();
}



?>

<?php include 'header.php'; ?>

<div class="row">
    <div class="col-md-6">
        <h2>Add New Booking</h2>
        <!-- Form for adding new bookings -->
        <form action="bookings_add.php" method="post">
            <!-- Add form fields based on your table columns -->
            <?php // Fetch customers from the database
$customers_query = "SELECT customer_id, full_name, phone_number FROM customers";
$customers_result = $conn->query($customers_query);
 ?>


            <div class="form-group">
                <label for="customer_id">Customer:</label>
                <select class="form-control select-search" name="customer_id" required>
                    <option value="">Select Customer</option>
    <?php while ($customer = $customers_result->fetch_assoc()) : ?>
        <option value="<?= $customer['customer_id']; ?>">
            <?= $customer['full_name'] . ' - ' . $customer['phone_number']; ?>
        </option>
    <?php endwhile; ?>
</select>
            </div>
<?php 

// Fetch rooms from the database
$rooms_query = "SELECT room_id, room_number, room_type FROM rooms";
$rooms_result = $conn->query($rooms_query);
 ?>


            <div class="form-group">
                <label for="room_id">Room</label>
                <select id="room_id" class="form-control select-search" name="room_id" required>
                    <option value="">Select Room</option>
    <?php while ($room = $rooms_result->fetch_assoc()) : ?>
        <option value="<?= $room['room_id']; ?>">
            <?= $room['room_number'] . ' - ' . $room['room_type']; ?>
        </option>
    <?php endwhile; ?>
</select>
            </div>
            <div class="form-group">
                <label for="check_in_date">Check-in Date:</label>
                <input id="check_in_date" type="datetime-local" class="form-control" name="check_in_date" required>
            </div>
            <div class="form-group">
                <label for="check_out_date">Check-out Date:</label>
                <input id="check_out_date" type="datetime-local" class="form-control" name="check_out_date" required>
            </div>
            <div class="form-group">
                <label for="total_cost">Total Cost:</label>
                <input id="total_cost" type="text" class="form-control" name="total_cost" required>
            </div>
            <div class="form-group">
                <label for="discount">Discount:</label>
                <input type="text" class="form-control" name="discount" value="0">
            </div>


            <div class="form-group">
                <label for="paid_amount">Paid Amount:</label>
                <input type="text" class="form-control" name="paid_amount" value="0">
            </div>
            <div class="form-group">
                <label for="male_guests">Male Guests:</label>
                <input type="text" class="form-control" name="male_guests" required>
            </div>
            <div class="form-group">
                <label for="female_guests">Female Guests:</label>
                <input type="text" class="form-control" name="female_guests" required>
            </div>
            <div class="form-group">
                <label for="children_guests">Children Guests:</label>
                <input type="text" class="form-control" name="children_guests" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Booking</button>
        </form>
    </div>
</div>


<?php include 'footer.php'; ?>
