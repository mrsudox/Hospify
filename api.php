<?php
// Include your database connection file
include 'config.php';

// Check if the required parameters are set
if (
isset($_GET['type']) &&
    $_GET['type'] == 'room_cost' &&
    isset($_GET['room_id']) &&
    isset($_GET['check_in_date']) &&
    isset($_GET['check_out_date'])

) {

    // Sanitize and store the parameters
    $room_id = intval($_GET['room_id']);
    $check_in_date = mysqli_real_escape_string($conn, $_GET['check_in_date']);
    $check_out_date = mysqli_real_escape_string($conn, $_GET['check_out_date']);

    // Fetch room rates from the rooms table
    $room_query = "SELECT rate FROM rooms WHERE room_id = $room_id";
    $room_result = $conn->query($room_query);

    if ($room_result && $room_result->num_rows > 0) {
        $room = $room_result->fetch_assoc();
        $rate = $room['rate'];

        // Calculate the number of days between check-in and check-out dates
        $start_date = new DateTime($check_in_date);
        $end_date = new DateTime($check_out_date);
        $interval = $start_date->diff($end_date);
        $num_of_days = $interval->days;

        // Calculate the room cost
        $room_cost = $rate * $num_of_days;

        // Prepare and return JSON response
        $response = array(
            'success' => true,
            'room_cost' => $room_cost
        );
        echo json_encode($response);
    } else {
        // Room not found
        $response = array(
            'success' => false,
            'message' => 'Room not found'
        );
        echo json_encode($response);
    }
} 








// Check if the type is set to additional_charges_add
if (isset($_GET['type']) && $_GET['type'] === 'additional_charges_add') {


    // Fetch the existing additional_charges JSON data from the database
    $bookingId = $_POST['booking_id'];
    $fetchChargesQuery = "SELECT additional_charges FROM bookings WHERE booking_id = $bookingId";
    $result = $conn->query($fetchChargesQuery);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $existingChargesJSON = $row['additional_charges'];

        // Decode the existing JSON data
        $existingCharges = json_decode($existingChargesJSON, true);

        // Get the current user info
        $currentUserInfo = getCurrentUserInfo();

        // Prepare the new charge data
        $newCharge = array(
            "timestamp" => date('Y-m-d H:i:s'), // Current timestamp
            "value" => $_POST['amount'], // Amount from the form
            "desc" => $_POST['description'], // Description from the form
            "added_from" => "hotel", // Fixed value for this function
            "added_by" => $currentUserInfo // Added by the current user
        );

        // Add the new charge to the existing charges array
        array_push($existingCharges, $newCharge);

        // Encode the updated data back to JSON
        $updatedChargesJSON = json_encode($existingCharges);

        // Update the additional_charges column in the bookings table
        $updateChargesQuery = "UPDATE bookings SET additional_charges = '$updatedChargesJSON' WHERE booking_id = $bookingId";
        $conn->query($updateChargesQuery);

        // Output the JSON response
        header('Content-Type: application/json');
        echo json_encode(array("message" => "Additional charge added successfully.", "error" => false));
    } else {
        // Handle the case where the booking is not found
        header('Content-Type: application/json');
        echo json_encode(array("message" => "Booking not found.", "error" => true));
    }
} 





if (isset($_GET['type']) && $_GET['type'] === 'add_paid_amount') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Assuming you have a function to get current user info
        $added_by = getCurrentUserInfo();

        // Collect form data
        $amount = $_POST['amount'];
        $desc = $_POST['description'];
        $added_from = $_POST['added_from'];
        $alternate_id = $_POST['alternate_id'];

        // Insert data into the cash_in table
        $query = "INSERT INTO cash_in (timestamp, value, description, added_by, added_from, alternate_id) VALUES (NOW(), ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("dsssi", $amount, $desc, $added_by, $added_from, $alternate_id);

        if ($stmt->execute()) {
            // Output the JSON response for success
            header('Content-Type: application/json');
            echo json_encode(array("message" => "Cash in amount added successfully.", "error" => false));
        } else {
            // Output the JSON response for failure
            header('Content-Type: application/json');
            echo json_encode(array("message" => "Error adding cash in amount.", "error" => true));
        }

        $stmt->close();
    } else {
        // Output the JSON response for invalid request method
        header('Content-Type: application/json');
        echo json_encode(array("message" => "Invalid request method.", "error" => true));
    }
}


if (isset($_GET['type']) && $_GET['type'] === 'get_paid_amount') {
    // Assuming you have a function to get current user info and a database connection
    $addedFrom = $_GET['added_from']; // Update this accordingly
    $alternateId = intval($_GET['alternate_id']);

    // Assuming you have the functions getTotalCashIn and getTotalCashOut
    $totalCashIn = getTotalCashIn($conn, $addedFrom, $alternateId);
    $totalCashOut = getTotalCashOut($conn, $addedFrom, $alternateId);

    // Calculate the total amount (subtracting totalCashOut from totalCashIn)
    $totalAmount = $totalCashIn - $totalCashOut;

    // Output the JSON response
    header('Content-Type: application/json');
    echo json_encode(array("amount" => $totalAmount, "error" => false));
    exit();
}




if (isset($_GET['type']) && $_GET['type'] === 'get_additional_charges') {

    $bookingId = $_GET['booking_id'];

    // Get additional charges data for the booking
    $additionalChargesJson = getAdditionalChargesForBookingJSON($conn, $bookingId);

    // Calculate the total additional charges
    $totalAdditionalCharges = calculateTotalAdditionalCharges($additionalChargesJson);

    // Output the JSON response
    header('Content-Type: application/json');
    echo json_encode(array("additionalCharges" => $totalAdditionalCharges, "error" => false));
    exit();
}



// Check if the necessary data is set in the GET request
if (isset($_GET['type']) && $_GET['type'] === 'restaurant_pos_sale') {
    // Get data from the GET request
    $totalAmount = floatval($_GET['totalAmount']);
    $mergedDesc = $_GET['mergedDesc'];

    // Get user information
    $userInfo = getCurrentUserInfo();

    // Prepare data for the cash_in table
    $timestamp = date('Y-m-d H:i:s');
    $value = intval($totalAmount);
    $description = $mergedDesc;
    $added_by = $userInfo; // Assuming 'username' is the key for the username in the user information
    $added_from = 'restaurant';
    $alternate_id = 0; // Assuming this field is blank



    $sql = "INSERT INTO cash_in (timestamp, value, description, added_by, added_from, alternate_id) VALUES ('$timestamp', $value, '$description', '$added_by', '$added_from', '$alternate_id')";

    if (mysqli_query($conn, $sql)) {
        echo "Data inserted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }


}



// Function to get POS bill data based on cash_in_id or fetch the last inserted ID
function getPosBillData($conn,$added_from,$cash_in_id = null,) {
    // Use $conn for database operations

    // Modify this part based on your actual database structure
    // This is just a placeholder
    if ($cash_in_id) {
        // Fetch data based on the provided cash_in_id
        $query = "SELECT * FROM cash_in WHERE cash_in_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $cash_in_id);
    } else {
        // Fetch the last inserted ID with added_from as 'restaurant'
        $query = "SELECT * FROM cash_in WHERE added_from = 'restaurant' ORDER BY cash_in_id DESC LIMIT 1";
        $stmt = mysqli_prepare($conn, $query);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if data is fetched successfully
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return $row;
    } else {
        return false;
    }
}

// Check if type is set to restaurant_pos_bill_print
if (isset($_GET['type']) && $_GET['type'] === 'restaurant_pos_bill_print') {
    // Check if cash_in_id is provided in the URL
    if (isset($_GET['cash_in_id'])) {
        $cash_in_id = $_GET['cash_in_id'];
    } else {
        // If cash_in_id is not provided, fetch the last inserted ID with 'added_from' as 'restaurant'
        $posBillData = getPosBillData($conn, "restaurant");
        $cash_in_id = $posBillData['cash_in_id'];
    }

    // Check if data is fetched successfully
    if ($cash_in_id) {
        // Extract individual items from the description
        $items = explode(', ', $posBillData['description']);
        $itemsHtml = '';
foreach ($items as $item) {
    $itemsHtml .= '<tr class="service">
                                <td class="tableitem center"><p class="itemtext">' . $item . '</p></td></tr>';
}
echo '
<style>
#invoice-POS{

  background: #FFF;
  
  
::selection {background: #f31544; color: #FFF;}
::moz-selection {background: #f31544; color: #FFF;}
h1{
  font-size: 1.5em;
  color: #222;
}
h2{font-size: .9em;}
h3{
  font-size: 1.2em;
  font-weight: 300;
  line-height: 2em;
}
p{
  font-size: .7em;
  color: #666;
  line-height: 1.2em;
}
 
#top, #mid,#bot{ /* Targets all id with \'col-\' */
  border-bottom: 1px solid #EEE;
}

#top{min-height: 100px;}
#mid{min-height: 80px;} 
#bot{ min-height: 50px;}

#top .logo{
  //float: left;
    height: 60px;
    width: 60px;
    background: url('.$setting_logo_url.') no-repeat;
    background-size: 60px 60px;
}
.clientlogo{
  float: left;
    height: 60px;
    width: 60px;
    background: url('.$setting_logo_url.') no-repeat;
    background-size: 60px 60px;
  border-radius: 50px;
}
.info{
  display: block;
  //float:left;
  margin-left: 0;
}
.title{
  float: right;
}
.title p{text-align: right;} 
table{
  width: 100%;
  border-collapse: collapse;
}
td{
  //padding: 5px 0 5px 15px;
  //border: 1px solid #EEE
}
.tabletitle{
  //padding: 5px;
  font-size: .5em;
  background: #EEE;
}
.service{border-bottom: 1px solid #EEE;}
.item{width: 24mm;}
.itemtext{font-size: .5em;}

#legalcopy{
  margin-top: 5mm;
}

    tr.tabletitle {
        text-align: center;
    }

    td.center {
        text-align: center;
    }  
  
}

</style>
  <div id="invoice-POS">
    
    <center id="top">
      <div class="logo"></div>
      <div class="info"> 
        <h2>'.$setting_company_name.'</h2>
      </div><!--End Info-->
    </center><!--End InvoiceTop-->
    
    <center id="mid">
      <div class="info">
        <h2>Contact Info</h2>
        <p> 
            Address : '.$setting_full_address.'</br>
            Email   : '.$setting_primary_email.'</br>
            Phone   : '.$setting_primary_contact.'</br>
        </p>
      </div>
    </center><!--End Invoice Mid-->
    
    <center id="bot">

                    <div id="table">
                        <table>
                            <tr class="tabletitle">
                                <td class="item center"><h2>Items</h2></td>

                            </tr>

                            

                            '.$itemsHtml.'

                            <tr class="tabletitle">

                                <td class="Rate center"><h2>Total : '.$posBillData['value'].'</h2></td>
       
                            </tr>

                        </table>
                    </div><!--End Table-->

                    <div id="legalcopy">
                        <p class="legal"><strong>Thank you for your business!</strong>   
                        </p>
                    </div>

                </center><!--End InvoiceBot-->
  </div><!--End Invoice-->





';

    } else {
        // If data retrieval fails, display an error message
        echo "Error: Unable to fetch POS bill data.";
    }
}




if (isset($_GET['type']) && $_GET['type'] === 'hotel_customer_options') {
  // Fetch customer details from the database
  $query = "SELECT customer_id, full_name, phone_number FROM customers";
  $result = mysqli_query($conn, $query);

  if ($result) {
    $customers = array();
    while ($row = mysqli_fetch_assoc($result)) {
      $customers[] = $row;
    }
    echo json_encode($customers);
  } else {
    echo json_encode(array('error' => 'Failed to fetch customer options'));
  }
} 


// Close the database connection
$conn->close();
?>
