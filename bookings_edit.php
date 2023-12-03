<?php
include 'config.php'; // Include your database connection file

// Check if booking_id is provided in the URL
if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    // Fetch booking details from the database
    $query = "SELECT * FROM bookings WHERE booking_id = $booking_id";
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

// Check if the form is submitted for updating
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

    // Update data in the bookings table
    $update_query = "UPDATE bookings SET customer_id='$customer_id', room_id='$room_id', check_in_date='$check_in_date', check_out_date='$check_out_date', total_cost='$total_cost', discount='$discount',  male_guests='$male_guests', female_guests='$female_guests', children_guests='$children_guests' WHERE booking_id = $booking_id";
    $conn->query($update_query);

    // Redirect to bookings.php after updating the booking
    header("Location: bookings.php");
    exit();
} else {
    // Fetch customer and room details for dropdowns
    $customers_query = "SELECT customer_id, full_name FROM customers";
    $customers_result = $conn->query($customers_query);

    $rooms_query = "SELECT room_id, room_number FROM rooms";
    $rooms_result = $conn->query($rooms_query);
}

?>

<?php include 'header.php'; ?>

<div class="row">
    <div class="col-md-6">
        <h2>Edit Booking</h2>
        <!-- Form for editing booking details -->
        <form action="bookings_edit.php?booking_id=<?= $booking_id; ?>" method="post">
<?php
// Fetch customers from the database
$customers_query = "SELECT customer_id, full_name, phone_number FROM customers";
$customers_result = $conn->query($customers_query);

// Fetch rooms from the database
$rooms_query = "SELECT room_id, room_number, room_type FROM rooms";
$rooms_result = $conn->query($rooms_query);

// Assume $selectedCustomerId and $selectedRoomId are the selected values you want to display as selected options
$selectedCustomerId = $booking['customer_id'];
$selectedRoomId = $booking['room_id'];
?>

<!-- Customer dropdown -->
<div class="form-group">
    <label for="customer_id">Customer:</label>
    <select class="form-control select-search" name="customer_id" required>
        <option value="">Select Customer</option>
        <?php while ($customer = $customers_result->fetch_assoc()) : ?>
            <option value="<?= $customer['customer_id']; ?>" <?= ($selectedCustomerId == $customer['customer_id']) ? 'selected' : ''; ?>>
                <?= $customer['full_name'] . ' - ' . $customer['phone_number']; ?>
            </option>
        <?php endwhile; ?>
    </select>
</div>

<!-- Room dropdown -->
<div class="form-group">
    <label for="room_id">Room</label>
    <select class="form-control select-search" name="room_id" required>
        <option value="">Select Room</option>
        <?php while ($room = $rooms_result->fetch_assoc()) : ?>
            <option value="<?= $room['room_id']; ?>" <?= ($selectedRoomId == $room['room_id']) ? 'selected' : ''; ?>>
                <?= $room['room_number'] . ' - ' . $room['room_type']; ?>
            </option>
        <?php endwhile; ?>
    </select>
</div>

            <div class="form-group">
                <label for="check_in_date">Check-in Date:</label>
                <input type="datetime-local" class="form-control" name="check_in_date" value="<?= $booking['check_in_date']; ?>" required>
            </div>
            <div class="form-group">
                <label for="check_out_date">Check-out Date:</label>
                <input type="datetime-local" class="form-control" name="check_out_date" value="<?= $booking['check_out_date']; ?>" required>
            </div>
            <div class="form-group">
                <label for="total_cost">Total Cost:</label>
                <input type="number" class="form-control" name="total_cost" value="<?= $booking['total_cost']; ?>" required>
            </div>
            <div class="form-group">
                <label for="discount">Discount:</label>
                <input type="number" class="form-control" name="discount" value="<?= $booking['discount']; ?>">
            </div>

<label for="additional_charges">Additional Charges:</label><br>
<div class="input-group mb-3">
    
    <input id="additional_charges" type="text" class="form-control" name="additional_charges" value="<?= calculateTotalAdditionalCharges($booking['additional_charges']) ?>" disabled>
    <div class="input-group-append">
        <button class="btn btn-outline-secondary" type="button" data-toggle="modal" data-target="#additionalChargesModal">
            <i class="fas fa-plus"></i>
        </button>
    </div>
</div>


<label for="paid_amount">Received Amount:</label><br>
<div class="input-group mb-3">
    
    <input type="text" id="paid_amount" class="form-control" name="paid_amount" value="<?=  getTotalCashIn($conn,"hotel",$booking['booking_id']) - getTotalCashOut($conn,"hotel",$booking['booking_id']) ?>" disabled>
    <div class="input-group-append">
        <button class="btn btn-outline-secondary" type="button" data-toggle="modal" data-target="#paidAmountModal">
            <i class="fas fa-plus"></i>
        </button>
    </div>
</div>

            <div class="form-group">
                <label for="male_guests">Male Guests:</label>
                <input type="number" class="form-control" name="male_guests" value="<?= $booking['male_guests']; ?>" required>
            </div>
            <div class="form-group">
                <label for="female_guests">Female Guests:</label>
                <input type="number" class="form-control" name="female_guests" value="<?= $booking['female_guests']; ?>" required>
            </div>
            <div class="form-group">
                <label for="children_guests">Children Guests:</label>
                <input type="number" class="form-control" name="children_guests" value="<?= $booking['children_guests']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Booking</button>
        </form>
    </div>
</div>

<!--Modal for Additional Charges-->                        
<div class="modal fade" id="additionalChargesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Additional Charges</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Add your form fields here -->
                <form id="additional_charges_add_form_modal" action="" method="post">
                    <input type="hidden" name="booking_id" value="<?= $booking['booking_id']; ?>">
                    <div class="form-group">
                        <label for="amount">Amount:</label>
                        <input type="number" class="form-control" name="amount" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control" name="description" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Charges</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!--Modal for Add Paid Amount-->                        
<div class="modal fade" id="paidAmountModal" tabindex="-1" role="dialog" aria-labelledby="paidAmountModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paidAmountModalLabel">Add Received Amount</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Add your form fields here -->
                <form id="add_paid_amount_modal" action="" method="post">
                    <input type="hidden" name="alternate_id" value="<?= $booking['booking_id']; ?>">
                    <div class="form-group">
                        <label for="amount">Amount:</label>
                        <input type="number" class="form-control" name="amount" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control" name="description" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Paid Amount</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// JavaScript function to update additional charges
function updateAdditionalCharges(bookingId) {
    // Make AJAX call to custom API
    $.get("api.php?type=get_additional_charges&booking_id=" + bookingId, function (response) {
        // Check the response
        if (response.error) {
            // Handle error
            alert("Error: " + response.message);
        } else {
            // Update the additional_charges element with the received value
            $("#additional_charges").val(response.additionalCharges);
        }
    }, "json");
}



// JavaScript function to update paid amount
function updatePaidAmount(alternateId, addedFrom) {
    // Make AJAX call to get_paid_amount API
    $.get("api.php?type=get_paid_amount&alternate_id=" + alternateId + "&added_from=" + addedFrom, function (response) {
        // Check the response
        if (response.error) {
            // Handle error
            alert("Error: " + response.message);
        } else {
            // Update the paid_amount input field with the received amount
            $("#paid_amount").val(response.amount);
        }
    }, "json");
}





    $(document).ready(function () {
        // Handle form submission
        $("#additional_charges_add_form_modal").submit(function (event) {
            event.preventDefault();

            // Serialize form data
            var formData = $(this).serialize();

            // Post data to api.php
            $.post("api.php?type=additional_charges_add", formData, function (response) {
                // Check the response
                if (response.error) {
                    // Handle error
                    alert("Error: " + response.message);
                } else {
                    // Handle success
                    alert("Success: " + response.message);
                        updateAdditionalCharges("<?= $booking['booking_id']; ?>");
                    // Close the modal (assuming you are using Bootstrap modal)
                    $("#additionalChargesModal").modal("hide");

                    // Optionally, you can update the UI or perform additional actions
                }
            }, "json");
        });
    });

$(document).ready(function () {
    // Handle form submission
    $("#add_paid_amount_modal").submit(function (event) {
        event.preventDefault();

        // Serialize form data
        var formData = $(this).serialize();

        // Manually add 'added_from' to the serialized data
        formData += "&added_from=hotel";

        // Post data to api.php
        $.post("api.php?type=add_paid_amount", formData, function (response) {
            // Check the response
            if (response.error) {
                // Handle error
                alert("Error: " + response.message);
            } else {
                // Handle success
                alert("Success: " + response.message);
                updatePaidAmount("<?= $booking['booking_id']; ?>", "hotel");
                // Close the modal (assuming you are using Bootstrap modal)
                $("#paidAmountModal").modal("hide");

                // Optionally, you can update the UI or perform additional actions
            }
        }, "json");
    });
});



</script>

<?php include 'footer.php'; ?>
