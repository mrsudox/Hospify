<?php 
include "config.php";

include "header.php";




 ?>



<input id="__total" type="" name="__total" value="" hidden>
<input id="__grand_total" type="" name="__grand_total" value="" hidden>
<input id="__discount" type="" name="__discount" value="" hidden>



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
            <div class="d-inline" id="total-amount" style="font-size: 1rem;">Total: 0.00</div><span> | </span>
<div class="d-inline" id="discount-amount-div" style="font-size: 1rem;">Discount: 0.00</div><br>
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
                    <input type="number" min="0" pattern="[0-9]*" inputmode="numeric" class="form-control" id="quantity" style="font-size: 2rem; text-align: center;" autofocus>
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
            <div class="modal-body">
                <div class="form-group">
                    <label for="cash-amount">Cash Amount:</label>
                    <input type="number" min="0" pattern="[0-9]*" inputmode="numeric" class="form-control" placeholder="Enter Cash Received" id="cash-amount" style="font-size: 2rem; text-align: center;" autofocus>
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
                <button id="reloadButton" style="flex: 1"; type="button" class="btn btn-secondary " id="new-sale-btn"> Close</button>
            </div>
        </div>
    </div>
</div>



<!--  Modal  for hotel customer-->
<div class="modal fade" id="hotelCustomerModal" tabindex="-1" role="dialog" aria-labelledby="hotelCustomerModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-body">
        <div id="customer_details"></div>
        <form id="hotelCustomerForm">

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="updateBtn">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<?php
$result = mysqli_query($conn, "SELECT * FROM menu_items");
$menuItems = [];

while ($row = mysqli_fetch_assoc($result)) {
    $menuItem = [
        'menu_id' => $row['menu_id'],
        'item_name' => $row['item_name'],
        'unit' => $row['unit'],
        'price' => floatval($row['price'])
    ];

    $menuItems[] = $menuItem;
}
?>

<script>
var menuItems = <?php echo json_encode($menuItems, JSON_HEX_QUOT | JSON_HEX_TAG); ?>;

function generateItemDescription(itemId, quantity) {
    var selectedItem = menuItems.find(item => item.menu_id == itemId);

    if (selectedItem) {
        var lineAmount = quantity * selectedItem.price;
        return selectedItem.item_name + ' - ' + quantity + ' (' + selectedItem.unit + ') X ' + selectedItem.price + ' = ' + lineAmount.toFixed(2);
    } else {
        return '';
    }
}

function getTotal() {
    updateTotal();
    return $('#__total').val();
}

function getGrandTotal() {
    return $('#__grand_total').val();
}

function getDiscount() {
    return $('#__discount').val();
}

function updateDiscount() {
    var discountAmount = parseFloat($('#discount-amount-input').val());
    if ((discountAmount !== '') && (discountAmount > -1) && (!isNaN(discountAmount))) {
        $('#__discount').val(discountAmount);
        $('#discount-amount-div').text('Discount: ' + discountAmount.toFixed(2));  
    } else {
            alert("Please Enter Valid Discount");
        }
    
}

function updateGrandTotal() {
    var totalAmount = getTotal();
    var discountAmount = getDiscount();
    var grandTotal = totalAmount - discountAmount;

    $('#__grand_total').val(grandTotal.toFixed(2));
    $('#grand-total-amount').text('Total: ' + grandTotal.toFixed(2));
}

function updateTotal() {
    var total = 0;
    $('.pos-item').each(function () {
        var itemText = $(this).find('.itemDescription').text();
        var itemPrice = parseFloat(itemText.match(/[\d\.]+$/)[0]);
        total += itemPrice;
    });

    $('#__total').val(total.toFixed(2));
    $('#total-amount').text('Total: ' + total.toFixed(2));
}



function getMergedDescription() {
    var mergedDesc = '';
    $('.pos-item .itemDescription').each(function () {
        mergedDesc += $(this).text() + ', ';
    });
    var discount__amount = $('#discount-amount-div').text();
    mergedDesc += discount__amount;
    mergedDesc = mergedDesc.slice(0, -2);

    return mergedDesc;
}

function printBill() {
    var printWindow = window.open('api.php?type=restaurant_pos_bill_print', '_blank', 'width=500,height=700');
    printWindow.onload = function () {
        printWindow.print();
        printWindow.close();
    };
}

function cashInputButtonPay() {
    var cashAmount = parseFloat($('#cash-amount').val());
    var totalAmount = getGrandTotal();

    if ((cashAmount < totalAmount) || (cashAmount == '') || (cashAmount < 0) || (isNaN(cashAmount))) {
        alert("Error: Insufficient cash amount. Please enter a valid amount.");
    } else {
        var change = cashAmount - totalAmount;
        var mergedDesc = getMergedDescription();

        $.ajax({
            url: 'api.php?type=restaurant_pos_sale',
            type: 'GET',
            data: { totalAmount: totalAmount, mergedDesc: mergedDesc },
            success: function (response) {
                $('#payment-success').text(response);
                $('#change-amount').text('Return: ' + change.toFixed(2));
                $('.print-btn-div').show();
                $('.pay-btn-div').hide();
            },
            error: function (xhr, status, error) {
                $('#payment-error').text('Error: ' + xhr.responseText);
            }
        });
    }
}

function getHotelCustomerProfile(customerNameID, callback) {

var description = getMergedDescription();
var grandTotal = getGrandTotal();


    $.ajax({
        url: 'api.php?type=getHotelCustomerProfile&customer_id=' + customerNameID,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            $("#hotelCustomerForm").html("");

            // Append input fields with labels to the form
            $('#hotelCustomerForm').append('<div class="form-group"><label for="customerName">Customer Name</label><input type="text" class="form-control" id="customerName_" name="customerName" value="' + data.full_name + '" readonly></div>');
            $('#hotelCustomerForm').append('<div class="form-group"><label for="phoneNo">Phone No</label><input type="text" class="form-control" id="phoneNo_" name="phoneNo" value="' + data.phone_number + '" readonly></div>');
            $('#hotelCustomerForm').append('<div class="form-group"><label for="dueBalance">Due Balance</label><input type="text" class="form-control" id="dueBalance_" name="dueBalance" value="' + data.total_net_balance + '" readonly></div>');
            $('#hotelCustomerForm').append('<div class="form-group"><label for="latestBookingId">Latest Booking Id</label><input type="text" class="form-control" id="latestBookingId_" name="latestBookingId" value="' + data.current_booking_id + '" readonly></div>');
            $('#hotelCustomerForm').append('<div class="form-group"><label for="totalThisBillAmount">Total This Bill Amount</label><input type="text" class="form-control" id="totalThisBillAmount_" name="totalThisBillAmount" value="' + grandTotal + '" readonly></div>');
            $('#hotelCustomerForm').append('<div class="form-group"><label for="billDesc">Description</label><input type="text" class="form-control" id="billDesc_" name="billDesc" value="' + description + '" readonly></div>');

            callback();
        },
        error: function (xhr, status, error) {
            // Call the callback function with an error
            callback(error);
        }
    });
}


function updateAdditionalCharges() {
    // Serialize the form data
    var formData = $('#hotelCustomerForm').serialize();

    // AJAX request to update additional charges using POST method
    $.ajax({
        url: 'api.php?type=updateAdditionalCharges',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function (response) {
            // Handle success response
            console.log('Update Successful:', response);

            // Show success alert
            alert(response.message);

            // Refresh the page only if the status is success
            if (response.status === 'success') {
                location.reload();
            }
        },
        error: function (xhr, status, error) {
            // Handle error response
            console.error('Update Failed:', error);

            // Show error alert
            alert('Update Failed: ' + error.statusText);
        }
    });
}






$(document).ready(function () {
    var selectedItemId;

    $('.btn-add-item').on('click', function () {
        selectedItemId = $(this).data('item-id');
        $('#quantity').val('');
    });

    $('.btn-quantity').on('click', function () {
        var currentQuantity = $('#quantity').val();
        var digit = $(this).text();
        $('#quantity').val(currentQuantity + digit);
    });

    $('.btn-cash-amount').on('click', function () {
        var currentAmount = $('#cash-amount').val();
        var digit = $(this).text();
        $('#cash-amount').val(currentAmount + digit);
    });

    $('.btn-proceed').on('click', function () {
        var quantity = $('#quantity').val();

        if ((quantity !== '') && (quantity > 0) && (!isNaN(quantity))) {
            var itemDescription = generateItemDescription(selectedItemId, quantity);
            $('#pos-bill-form').append(
                '<div class="row pos-item" style="margin-bottom: 5px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">' +
                '<div class="col-md-8 itemDescription">' + itemDescription + '</div>' +
                '<div class="col-md-2"><button class="btn btn-danger button-sm px-1 btn-delete-item" style="padding: 0px;">delete</button></div>' +
                '</div>'
            );

            updateGrandTotal();
            $('#addItemModal').modal('hide');
        } else {
            alert("Please Enter Valid Quantity");
        }
    });

    $('#pos-bill-form').on('click', '.btn-delete-item', function () {
        $(this).closest('.pos-item').remove();
        updateTotal();
        updateGrandTotal();
    });

    $('#discount-add-btn').on('click', function (event) {
        event.preventDefault();
        updateDiscount();
        updateGrandTotal();
    });

    $('#btn-pay-now').on('click', function () {

        var total_amount = getGrandTotal();
        if ((total_amount !== '') && (total_amount > 0) && (!isNaN(total_amount))) {
            $('#cashInputModal').modal('show');
            var pay_modal_total_amount = $('#grand-total-amount').text();
            $('#pay-modal-total-amount').text(pay_modal_total_amount);
            $('#cash-amount').focus();
        
        } else {
            alert("Please Check Entry: Total amount should not be less than 0(zero)");
        }

        
    });

    $('#reloadButton').on('click', function () {
        location.reload();
    });

    $("#btn-add-to-hotel-customer").click(function () {
        var customerNameID = $("#customerName").val();

        if (customerNameID > 0) {

            $('#hotelCustomerModal').modal('show');
            getHotelCustomerProfile(customerNameID, function (result, error) {
    if (error) {
        alert('Error:', error);
    }
});
            
        } else {
            alert("Please Select Hotel Customer");
        }
    });

    $("#updateBtn").click(function () {
        updateAdditionalCharges();
        
    });

    $('#btn-pay').on('click', function () {
        cashInputButtonPay();
    });
});
</script>





 <?php include "footer.php"; ?>