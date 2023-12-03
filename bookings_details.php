<?php
include 'config.php'; // Include your database connection file

// Check if booking_id is provided in the URL
if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    // Fetch booking details from the database
    $query = "SELECT * FROM bookings
              JOIN customers ON bookings.customer_id = customers.customer_id
              JOIN rooms ON bookings.room_id = rooms.room_id
              WHERE booking_id = $booking_id";

    $result = $conn->query($query);
    $booking = $result->fetch_assoc();

    if (!$booking) {
        // Redirect to bookings.php if booking_id is not found
        header("Location: bookings.php");
        exit();
    }
} else {
    // Redirect to bookings.php if booking_id is not provided
    header("Location: bookings.php");
    exit();
}

?>

<?php include 'header.php'; ?>

<div class="row">
    <div class="col-md-12">
        <h2>Booking Details</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Booking ID: <?= $booking['booking_id']; ?></h5>
                <p class="card-text">Customer: <?= $booking['full_name']; ?></p>
                <p class="card-text">Room: <?= $booking['room_number']; ?></p>
                <p class="card-text">Check-in Date: <?= $booking['check_in_date']; ?></p>
                <p class="card-text">Check-out Date: <?= $booking['check_out_date']; ?></p>
                <p class="card-text">Total Cost: <?= $booking['total_cost']; ?></p>
                <p class="card-text">Discount: <?= $booking['discount']; ?></p>
                <p class="card-text">Additional Charges: <?= calculateTotalAdditionalCharges($booking['additional_charges']) ?></p>
                <p class="card-text">Paid Amount: <?= getTotalCashIn($conn,"hotel",$booking['booking_id']) - getTotalCashOut($conn,"hotel",$booking['booking_id']) ?></p>
                <?php
// Assuming $booking is the associative array representing a booking
$balance_to_pay = $booking['total_cost'] - $booking['discount'] + calculateTotalAdditionalCharges($booking['additional_charges']) - getTotalCashIn($conn,"hotel",$booking['booking_id']) + getTotalCashOut($conn,"hotel",$booking['booking_id']);
?>

				<p class="card-text <?php if ($balance_to_pay < 0) { echo "text-danger"; } ?>">Balance to Pay: <?= $balance_to_pay; ?><?php if ($balance_to_pay < 0) { echo " <i class='fa-solid fa-circle-exclamation'></i>"; } ?></p>
                <p class="card-text">Male Guests: <?= $booking['male_guests']; ?></p>
                <p class="card-text">Female Guests: <?= $booking['female_guests']; ?></p>
                <p class="card-text">Children Guests: <?= $booking['children_guests']; ?></p>

            </div>
        </div>
    </div>
</div>


<?php displayAdditionalChargesTable($conn, $booking['booking_id']) ?>
<?php displayCashInTable($conn, "hotel", $booking['booking_id']); ?>
<?php displayCashOutTable($conn, "hotel", $booking['booking_id']); ?>
<?php include 'footer.php'; ?>
