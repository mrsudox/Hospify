<?php 
include "config.php";

include "header.php";




 ?>







<div class="container-fluid">
    <div class="row">
<div class="col-md-6">

<select class="select-search" id="customerName" name="customerName">
   
            <?php // Fetch customers from the database
$customers_query = "SELECT customer_id, full_name, phone_number FROM customers";
$customers_result = $conn->query($customers_query);
 ?>

                    <option value="">Select Customer</option>
    <?php while ($customer = $customers_result->fetch_assoc()) : ?>
        <option value="<?= $customer['customer_id']; ?>">
            <?= $customer['full_name'] . ' - ' . $customer['phone_number']; ?>
        </option>
    <?php endwhile; ?>



            </select><small><button title="Add this bill to hotel customer" type="button" id="btn-add-to-hotel-customer" style="margin-top: -5px; font-size: 0.8rem;" class="btn btn-danger btn-sm m-1"><i class="fa-solid fa-hotel"></i> Add this bill to hotel customer</button></small>

    <div class="card p-4">
        <!-- Menu Items Table -->
        <table class="table">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Unit</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Display menu items dynamically from the database, including the custom discount item -->
<?php
// Assume $conn is your database connection
$result = mysqli_query($conn, "SELECT * FROM menu_items");
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>{$row['item_name']}</td>";
    echo "<td>{$row['unit']}</td>";
    echo "<td>{$row['price']}</td>";
    echo "<td><button class='btn btn-primary btn-add-item' data-item-id='{$row['menu_id']}' data-toggle='modal' data-target='#addItemModal'>Add</button></td>";
    echo "</tr>";
}


?>

            </tbody>
        </table>
    </div>
</div>


<div class="col-md-6">
    <div class="px-2">
        <form id="pos-bill-form" method="post" action="process_payment.php">

            <!-- Items will be dynamically added here using JavaScript -->
            <div id="pos-bill"></div>

            <!-- Total Amount with inline CSS -->
            <div id="total-amount" style="font-size: 1rem;">Total: 0.00</div>
            <div id="discount-amount-div" style="font-size: 1rem;">Discount: 0.00</div>
            <input type="number" id="discount-amount-input" value="0" name="discount-amount-input"><button class="btn btn-secondary py-1 ml-1" id="discount-add-btn">add discount</button>
            <!-- Total Amount with inline CSS -->
            <div id="grand-total-amount" style="font-size: 3rem; color: #28a745; margin-bottom: 0.1rem;">Total: 0.00</div>

            <!-- Pay Now Button with inline CSS -->
            <button type="button" id="btn-pay-now" style="margin-top: 0px; font-size: 1.2rem;width: 100%;" class="btn btn-success mb-2">Pay Now</button>
            


        </form>
    </div>
</div>



<!-- Modal for Adding Item Quantity -->
<div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addItemModalLabel">Add Item Quantity</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="text" class="form-control" id="quantity" style="font-size: 2rem; text-align: center;">
                </div>
                <div class="calculator-buttons">
                    <button class="btn btn-secondary m-1 px-4 btn-quantity" style="flex: 1; font-size: 3rem;">1</button>
                    <button class="btn btn-secondary m-1 px-4 btn-quantity" style="flex: 1; font-size: 3rem;">2</button>
                    <button class="btn btn-secondary m-1 px-4 btn-quantity" style="flex: 1; font-size: 3rem;">3</button>
                    
                    <button class="btn btn-secondary m-1 px-4 btn-quantity" style="flex: 1; font-size: 3rem;">4</button>
                    <button class="btn btn-secondary m-1 px-4 btn-quantity" style="flex: 1; font-size: 3rem;">5</button><br>
                    <button class="btn btn-secondary m-1 px-4 btn-quantity" style="flex: 1; font-size: 3rem;">6</button>
                    
                    
                    <button class="btn btn-secondary m-1 px-4 btn-quantity" style="flex: 1; font-size: 3rem;">7</button>
                    <button class="btn btn-secondary m-1 px-4 btn-quantity" style="flex: 1; font-size: 3rem;">8</button>
                    <button class="btn btn-secondary m-1 px-4 btn-quantity" style="flex: 1; font-size: 3rem;">9</button>
                    <button class="btn btn-secondary m-1 px-4 btn-quantity" style="flex: 1; font-size: 3rem;">0</button>
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-proceed" style="flex: 1;">Proceed</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" style="flex: 1;">Cancel</button>
            </div>
        </div>
    </div>
</div>




        <div class="col-md-4">
            <!-- POS Bill Format -->
            <div id="pos-bill">
                <!-- Items will be dynamically added here using JavaScript -->
            </div>
        </div>
    </div>
</div>


<!-- Modal for Cash Input -->
<div class="modal fade" id="cashInputModal" tabindex="-1" role="dialog" aria-labelledby="cashInputModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cashInputModalLabel">Enter Cash Amount</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="cash-amount">Cash Amount:</label>
                    <input type="text" class="form-control" placeholder="Enter Cash Received" id="cash-amount" style="font-size: 2rem; text-align: center;" autofocus>
                </div>

                <div class="calculator-buttons">
                    <button class="btn btn-secondary m-1 px-4 btn-cash-amount" style="flex: 1; font-size: 3rem;">1</button>
                    <button class="btn btn-secondary m-1 px-4 btn-cash-amount" style="flex: 1; font-size: 3rem;">2</button>
                    <button class="btn btn-secondary m-1 px-4 btn-cash-amount" style="flex: 1; font-size: 3rem;">3</button>
                    
                    <button class="btn btn-secondary m-1 px-4 btn-cash-amount" style="flex: 1; font-size: 3rem;">4</button>
                    <button class="btn btn-secondary m-1 px-4 btn-cash-amount" style="flex: 1; font-size: 3rem;">5</button><br>
                    <button class="btn btn-secondary m-1 px-4 btn-cash-amount" style="flex: 1; font-size: 3rem;">6</button>
                    
                    
                    <button class="btn btn-secondary m-1 px-4 btn-cash-amount" style="flex: 1; font-size: 3rem;">7</button>
                    <button class="btn btn-secondary m-1 px-4 btn-cash-amount" style="flex: 1; font-size: 3rem;">8</button>
                    <button class="btn btn-secondary m-1 px-4 btn-cash-amount" style="flex: 1; font-size: 3rem;">9</button>
                    <button class="btn btn-secondary m-1 px-4 btn-cash-amount" style="flex: 1; font-size: 3rem;">0</button>
                    
                </div>
                <div id="pay-modal-total-amount" class="h4 font-weight-bold text-primary mb-3"></div>
                <div id="change-amount" class="h4 font-weight-bold text-success mb-1"></div>
                <div id="payment-error" class="h5 text-danger mb-1"></div>
                <div id="payment-success" class="h5 text-success mb-1"></div>
                
            </div>
            <div class="modal-footer pay-btn-div">
                <button type="button" style="flex: 1"; class="btn btn-primary " id="btn-pay"><i class="fas fa-money-bill"></i> Pay</button>
                <button type="button" style="flex: 1"; class="btn btn-secondary " data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
            </div>
            <div class="modal-footer print-btn-div" style="display: none;">
                <!-- Print and Add New Sale buttons -->
                <button id="print_btn" type="button" class="btn btn-success" onclick="printBill()">
    <i class="fas fa-print"></i> Print
</button>
                <button id="reloadButton" style="flex: 1"; type="button" class="btn btn-info " id="new-sale-btn"><i class="fas fa-plus"></i> Add New Sale</button>
            </div>
        </div>
    </div>
</div>



<!-- Bootstrap Modal -->
<div class="modal fade" id="hotelCustomerModal" tabindex="-1" role="dialog" aria-labelledby="hotelCustomerModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="hotelCustomerModalLabel">Add to Hotel Customer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="hotelCustomerForm">
          <div class="form-group">

          </div>
          <div class="form-group">
            <label for="totalAmount">Total Amount</label>
            <input type="text" class="form-control" id="customers-modal-total-amount" name="totalAmount" readonly>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="updateBtn">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<script>


<?php
// Assume $conn is your database connection
$result = mysqli_query($conn, "SELECT * FROM menu_items");
$menuItems = [];

while ($row = mysqli_fetch_assoc($result)) {
    $menuItem = [
        'menu_id' => $row['menu_id'],
        'item_name' => $row['item_name'],
        'unit' => $row['unit'],
        'price' => floatval($row['price']) // Ensure the price is treated as a float
    ];

    $menuItems[] = $menuItem;
}

?>

$(document).ready(function () {
    var selectedItemId;
    var menuItems = <?php echo json_encode($menuItems, JSON_HEX_QUOT | JSON_HEX_TAG); ?>;

console.log(menuItems);

    // Handle 'Add' button click to open the modal
    $('.btn-add-item').on('click', function () {
        selectedItemId = $(this).data('item-id');
        $('#quantity').val(''); // Reset the quantity input
    });

    // Handle quantity button click in the modal
    $('.btn-quantity').on('click', function () {
        var currentQuantity = $('#quantity').val();
        var digit = $(this).text();
        $('#quantity').val(currentQuantity + digit);
    });

     // Handle quantity button click in the modal
    $('.btn-cash-amount').on('click', function () {
        var currentAmount = $('#cash-amount').val();
        var digit = $(this).text();
        $('#cash-amount').val(currentAmount + digit);
    });


    // Handle 'Proceed' button click in the modal
    $('.btn-proceed').on('click', function () {
        var quantity = $('#quantity').val();

        // Check if quantity is entered
        if (quantity !== '') {
            // Generate the item description using the formula
            var itemDescription = generateItemDescription(selectedItemId, quantity);

// Append the item to the POS bill form
$('#pos-bill-form').append(
    '<div class="row pos-item" style="margin-bottom: 5px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">' +
    '<div class="col-md-8 itemDescription">' + itemDescription + '</div>' +
    '<div class="col-md-2"><button class="btn btn-danger button-sm px-1 btn-delete-item" style="padding: 0px;">delete</button></div>' +
    '</div>'
);


            // Calculate and update the total
            updateTotal();
            updateGrandTotal();

            // Close the modal
            $('#addItemModal').modal('hide');
        }
    });

    // Handle delete button click for dynamically added items
    $('#pos-bill-form').on('click', '.btn-delete-item', function () {
        $(this).closest('.pos-item').remove();
        updateTotal();
        updateGrandTotal();
    });



// Function to generate item description using the formula
// Function to generate item description using the formula
function generateItemDescription(itemId, quantity) {
    // You need to fetch item details from the server based on itemId
    // For now, let's assume you have a global variable 'menuItems' containing item details
    var selectedItem = menuItems.find(item => item.menu_id == itemId); // Use == for loose comparison

    // Check if the item is found
    if (selectedItem) {
        var lineAmount = quantity * selectedItem.price;
        return selectedItem.item_name + ' - ' + quantity + ' (' + selectedItem.unit + ') X ' + selectedItem.price + ' = ' + lineAmount.toFixed(2);
    } else {
        return '';
    }
}
$('#discount-add-btn').on('click', function (event) {
            // Prevent the default behavior of the button (e.g., form submission)
            event.preventDefault();

            // Call the updateGrandTotal function
            updateGrandTotal();
        });

    // Function to update the total in real-time
    function updateGrandTotal() {
        var totalAmount = parseFloat($('#total-amount').text().replace('Total: ', ''));
        console.log(totalAmount);
        var discountAmount = parseFloat($('#discount-amount-input').val());
        console.log(discountAmount);
        var grandTotal = totalAmount - discountAmount;
        console.log(grandTotal);

        // Update the total in the UI
        $('#grand-total-amount').text('Total: ' + grandTotal.toFixed(2));
        $('#discount-amount-div').text('Discount: ' + discountAmount.toFixed(2));
    }

    // Function to update the total in real-time
    function updateTotal() {
        var total = 0;
        $('.pos-item').each(function () {
            var itemText = $(this).find('.itemDescription').text();
            var itemPrice = parseFloat(itemText.match(/[\d\.]+$/)[0]);
            total += itemPrice;
        });

        // Update the total in the UI
        $('#total-amount').text('Total: ' + total.toFixed(2));
    }

    // Function to handle the payment (placeholder for your actual implementation)
    function handlePayment() {
        // Submit the POS bill form (placeholder for your actual implementation)
        $('#pos-bill-form').submit();
    }
});


// Handle 'Pay Now' button click
$('#btn-pay-now').on('click', function () {
    // Display the cash input modal
    $('#cashInputModal').modal('show');
    var pay_modal_total_amount = $('#grand-total-amount').text();
    
        $('#pay-modal-total-amount').text(pay_modal_total_amount);
        $('#cash-amount').focus();
});

// Handle 'Pay' button click inside the cash input modal
$('#btn-pay').on('click', function () {
    // Get the entered cash amount
    var cashAmount = parseFloat($('#cash-amount').val());

    // Get the total amount from the POS bill
    var totalAmount = parseFloat($('#grand-total-amount').text().replace('Total: ', ''));

    // Validate if the cash amount is sufficient
    if (cashAmount < totalAmount) {
        // Display an error message in the modal
        $('#payment-error').text('Error: Insufficient cash amount. Please enter a valid amount.');
    } else {
        // Calculate change
        var change = cashAmount - totalAmount;
        var discount__amount = $('#discount-amount-div').text();
        console.log("discount__amount:"+ discount__amount);
        // Get the merged description
        var mergedDesc = getMergedDescription(discount__amount);

// Assuming you have a function to handle the payment through AJAX
$.ajax({
    url: 'api.php?type=restaurant_pos_sale',
    type: 'GET',
    data: { totalAmount: totalAmount, mergedDesc: mergedDesc },
    success: function (response) {
        // Display success message in the modal
        $('#payment-success').text(response);

        // Display change in the modal
        $('#change-amount').text('Return: ' + change.toFixed(2));

        // Show the 'Print' and 'Add New Sale' buttons
        // Change the display property to 'block' for the element with class .print-btn-div
$('.print-btn-div').show();
$('.pay-btn-div').hide();

    },
    error: function (xhr, status, error) {
        // Display error message in the modal
        $('#payment-error').text('Error: ' + xhr.responseText);
    }
});

    }
});

 $(document).ready(function () {
        // Attach click event to the button
        $('#reloadButton').on('click', function () {
            // Reload the page
            location.reload();
        });
        
    });

// Function to get the merged description from POS bill items
function getMergedDescription(discount__amount) {
    var mergedDesc = '';

    $('.pos-item .itemDescription').each(function () {
        mergedDesc += $(this).text() + ', ';
    });

    // Add the discounted amount at the end of the description
    mergedDesc +=  discount__amount;

    // Remove the trailing comma and space
    mergedDesc = mergedDesc.slice(0, -2);

    return mergedDesc;
}


function printBill() {
    // Open a new window with a fixed size
    var printWindow = window.open('api.php?type=restaurant_pos_bill_print', '_blank', 'width=500,height=700');

    // Add an onload event to the new window
    printWindow.onload = function () {
        // Print the contents of the new window
        printWindow.print();

        // Close the window after printing
        printWindow.close();
    };
}


  $(document).ready(function() {
    // Button click event
    $("#btn-add-to-hotel-customer").click(function() {
    	$('#hotelCustomerModal').modal('show');
    	

    });

    // Update button click event
    $("#updateBtn").click(function() {
      // Add your code to handle the update logic
      // You can get the selected customer ID and total amount using $('#customerName').val() and $('#totalAmount').val()
    });
  });




</script>




 <?php include "footer.php"; ?>