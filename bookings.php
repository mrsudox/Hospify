<?php
include 'config.php'; // Include your database connection file

// Fetch bookings with customer names from the database
$query = "SELECT bookings.*, customers.full_name
          FROM bookings
          JOIN customers ON bookings.customer_id = customers.customer_id 
          ORDER BY bookings.booking_id DESC";

$result = $conn->query($query);

// Check for query execution error
if (!$result) {
    echo 'Error executing query: ' . $conn->error;
    exit;
}

?>

<?php include 'header.php'; ?>

<!-- Display bookings in a table -->
<div class="row">
    <div class="col-md-12">
        <h2>Bookings List</h2>
        <a href="bookings_add.php" class="btn btn-primary my-2"><i class="fa-solid fa-plus"></i> Add Bookings</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Check-in Date</th>
                    <th>Check-out Date</th>
                    <th>Balance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Check if the query was successful before fetching data
                if ($result instanceof mysqli_result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) :
                ?>
                        <tr>
                            <td><a href="bookings_details.php?booking_id=<?= $row['booking_id']; ?>"><?= $row['booking_id']; ?></a></td>
                            <td><?= $row['full_name']; ?></td>
                            <td><?= $row['check_in_date']; ?></td>
                            <td><?= $row['check_out_date']; ?></td>

                            <?php
                            // Assuming $booking is the associative array representing a booking
                            $balance_to_pay = $row['total_cost'] - $row['discount'] + calculateTotalAdditionalCharges($row['additional_charges']) - getTotalCashIn($conn,"hotel",$row['booking_id']) + getTotalCashOut($conn,"hotel",$row['booking_id']);
                            ?>

                            <td class="<?php if ($balance_to_pay < 0) { echo "text-danger"; } ?>">
    <?= $balance_to_pay; ?><?php if ($balance_to_pay < 0) { echo " <i class='fa-solid fa-circle-exclamation'></i>"; } ?>
</td>

                            <td>
                                <a title="Edit Booking Details" href="bookings_edit.php?booking_id=<?= $row['booking_id']; ?>" class="btn btn-primary btn-sm"><i class="fa-regular fa-pen-to-square"></i></a>
                                <a title="Delete Booking (Cannot Restore)" href="bookings_delete.php?booking_id=<?= $row['booking_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this booking?')"><i class="fa-solid fa-trash"></i></a>
                                <a title="View Booking Details" href="bookings_details.php?booking_id=<?= $row['booking_id']; ?>" class="btn btn-secondary btn-sm"><i class="fa-regular fa-eye"></i></a>
                                
                            </td>
                        </tr>
                <?php
                    endwhile;
                } else {
                    // Handle the case where there are no rows
                    echo '<tr><td colspan="6">No bookings found</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
