<?php

function checkBookingErrors($booking_id, $conn)
{
    // Fetch booking data from the database
    $booking_query = "SELECT * FROM bookings WHERE booking_id = $booking_id";
    $booking_result = $conn->query($booking_query);

    if ($booking_result && $booking_result->num_rows > 0) {
        $booking = $booking_result->fetch_assoc();

        // Initialize the result array
        $result = array(
            'error' => false,
            'warning' => false,
            'error_desc' => '',
            'warning_desc' => ''
        );

        // Check conditions
        if ($booking['total_cost'] === null || $booking['discount'] === null || $booking['additional_charges'] === null || $booking['paid_amount'] === null) {
            // Handle cases where required fields are null
            $result['error'] = true;
            $result['error_desc'] = 'Booking data is incomplete.';
        } else {
            // Calculate balance to pay
            $balance_to_pay = $booking['total_cost'] - $booking['discount'] + $booking['additional_charges'] - $booking['paid_amount'];

            // Check conditions
            if ($balance_to_pay < 0) {
                // Negative balance indicates an error
                $result['error'] = true;
                $result['error_desc'] = 'Negative balance to pay.';
            } elseif ($booking['discount'] > ($booking['total_cost'] + $booking['additional_charges'])) {
                // Too much discount may be a warning
                $result['warning'] = true;
                $result['warning_desc'] = 'High discount applied.';
            }
        }

        return $result;
    } else {
        // Handle the case where the booking is not found
        return array('error' => true, 'error_desc' => 'Booking not found.');
    }
}


// Function to get the current user info
function getCurrentUserInfo() {
    // Your implementation here
    $currentUserId = $_SESSION['user_id'];
    $currentUsername = $_SESSION['username'];
    return $currentUserId.":".$currentUsername;
}

function getAdditionalChargesForBookingJSON($conn, $bookingId) {
    $query = "SELECT additional_charges FROM bookings WHERE booking_id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $additionalChargesJson = $row['additional_charges'] ?? '[]';

    $stmt->close();

    return $additionalChargesJson;
}

function calculateTotalAdditionalCharges($additionalChargesJSON)
{
    // Decode the JSON string into an associative array
    $additionalChargesArray = json_decode($additionalChargesJSON, true);

    // Initialize total amount
    $totalAmount = 0;

    // Loop through each entry in the additional charges array
    foreach ($additionalChargesArray as $charge) {
        // Add the value to the total amount
        $totalAmount += $charge['value'];
    }

    // Return the total amount with 2 decimal points
    return number_format($totalAmount, 2);
}



function generateBookingsTableForCustomer($customer_id, $conn)
{
    // Fetch bookings with customer names from the database
    $query = "SELECT bookings.*, customers.full_name
              FROM bookings
              JOIN customers ON bookings.customer_id = customers.customer_id
              WHERE bookings.customer_id = $customer_id
              ORDER BY bookings.booking_id DESC";

    $result = $conn->query($query);

    echo '
    <!-- Display bookings in a table -->
    <div class="row">
        <div class="col-md-12">
            <h2>Bookings List for Customer</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Check-in Date</th>
                        <th>Check-out Date</th>
                        <th>Balance To Pay</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>';

    while ($row = $result->fetch_assoc()) {
        $balance_to_pay = $row['total_cost'] - $row['discount'] + calculateTotalAdditionalCharges($row['additional_charges']) - $row['paid_amount'];

        echo '
                    <tr>
                        <td><a href="bookings_details.php?booking_id=' . $row['booking_id'] . '">' . $row['booking_id'] . '</a></td>
                        <td>' . $row['check_in_date'] . '</td>
                        <td>' . $row['check_out_date'] . '</td>
                        <td class="';

        if ($balance_to_pay < 0) {
            echo 'text-danger">';
        } else {
            echo '">';
        }

        echo $balance_to_pay;

        if ($balance_to_pay < 0) {
            echo ' <i class="fa-solid fa-circle-exclamation"></i>';
        }

        echo '</td>
                    <td>
                                <a  title="Edit Booking Details"  href="bookings_edit.php?booking_id='.$row['booking_id'].'" class="btn btn-primary btn-sm"><i class="fa-regular fa-pen-to-square"></i></a>
                                <a title="Delete Booking (Cannot Restore)"  href="bookings_delete.php?booking_id='.$row['booking_id'].'" class="btn btn-danger btn-sm" onclick="return confirm("Are you sure you want to delete this booking?")"><i class="fa-solid fa-trash"></i></a>
                                <a title="View Booking Details" href="bookings_details.php?booking_id='.$row['booking_id'].'" class="btn btn-secondary btn-sm"><i class="fa-regular fa-eye"></i></a>
                            </td>
                    </tr>';
    }

    echo '
                </tbody>
            </table>
        </div>
    </div>';
}


function getTotalCashIn($conn, $addedFrom, $alternateId) {
    $query = "SELECT SUM(value) AS total_amount FROM cash_in WHERE added_from = ? AND alternate_id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $addedFrom, $alternateId);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $totalAmount = $row['total_amount'] ?? 0.00;

    $stmt->close();

    return $totalAmount;
}

// Function to calculate total cash_out amount
function getTotalCashOut($conn, $addedFrom, $alternateId) {
    $query = "SELECT SUM(value) AS total_amount FROM cash_out WHERE added_from = ? AND alternate_id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $addedFrom, $alternateId);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $totalAmount = $row['total_amount'] ?? 0.00;

    $stmt->close();

    return $totalAmount;
}




function displayAdditionalChargesTable($conn, $bookingId) {
    $query = "SELECT additional_charges FROM bookings WHERE booking_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $bookingData = $result->fetch_assoc();
        $additionalChargesJson = $bookingData['additional_charges'] ?? '[]';
        $additionalChargesArray = json_decode($additionalChargesJson, true);

        // Output the HTML table with Bootstrap classes
        echo '<div class="table-responsive">
                <h2 class="my-4">Additional Charges List </h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Timestamp</th>
                            <th>Value</th>
                            <th>Description</th>
                            <th>Added From</th>
                            <th>Added By</th>
                            <!-- Add more columns as needed -->
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($additionalChargesArray as $charge) {
            echo '<tr>';
            echo '<td>' . $charge['timestamp'] . '</td>';
            echo '<td>' . $charge['value'] . '</td>';
            echo '<td>' . $charge['desc'] . '</td>';
            echo '<td>' . $charge['added_from'] . '</td>';
            echo '<td>' . $charge['added_by'] . '</td>';
            // Add more columns as needed
            echo '</tr>';
        }

        echo '</tbody>
            </table>
        </div>';
    } else {
        echo 'No data found for the given booking ID.';
    }

    $stmt->close();
}




function displayCashInTable($conn, $addedFrom, $alternateId) {
    $query = "SELECT * FROM cash_in WHERE added_from = ? AND alternate_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $addedFrom, $alternateId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Output the HTML table with Bootstrap classes
        echo '<div class="table-responsive">
        <h2 class="my-4">Cash Received </h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Timestamp</th>
                            <th>Value</th>
                            <th>Description</th>
                            <th>Added By</th>
                            <!-- Add more columns as needed -->
                        </tr>
                    </thead>
                    <tbody>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['timestamp'] . '</td>';
            echo '<td>' . $row['value'] . '</td>';
            echo '<td>' . $row['description'] . '</td>';
            echo '<td>' . $row['added_by'] . '</td>';
            // Add more columns as needed
            echo '</tr>';
        }

        echo '</tbody>
            </table>
        </div>';
    } else {
        echo 'No cash received data found for the given booking id number.';
    }

    $stmt->close();
}



function displayCashOutTable($conn, $addedFrom, $alternateId) {
    $query = "SELECT * FROM cash_out WHERE added_from = ? AND alternate_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $addedFrom, $alternateId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Output the HTML table with Bootstrap classes
        echo '<div class="table-responsive">
        <h2 class="my-4">Cash Returned </h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Timestamp</th>
                            <th>Value</th>
                            <th>Description</th>
                            <th>Added By</th>
                            <!-- Add more columns as needed -->
                        </tr>
                    </thead>
                    <tbody>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['timestamp'] . '</td>';
            echo '<td>' . $row['value'] . '</td>';
            echo '<td>' . $row['description'] . '</td>';
            echo '<td>' . $row['added_by'] . '</td>';
            // Add more columns as needed
            echo '</tr>';
        }

        echo '</tbody>
            </table>
        </div>';
    } else {
        echo 'No cash returned data found for the given booking id number.';
    }

    $stmt->close();
}






?>
