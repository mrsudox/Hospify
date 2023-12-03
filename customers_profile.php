<?php
include 'config.php'; // Include your database connection file

// Check if customer_id is provided in the URL
if (isset($_GET['customer_id'])) {
    $customer_id = $_GET['customer_id'];

    // Fetch customer details from the database
    $query = "SELECT * FROM customers WHERE customer_id = $customer_id";
    $result = $conn->query($query);
    $customer = $result->fetch_assoc();

    if (!$customer) {
        // Redirect to customers.php if customer_id is not found
        header("Location: customers.php");
        exit();
    }
} else {
    // Redirect to customers.php if customer_id is not provided
    header("Location: customers.php");
    exit();
}

?>

<?php include 'header.php'; ?>

<div class="row">
    <div class="col-md-6">
        <h2>Customer Profile</h2>
        <dl class="row">
            <dt class="col-sm-4">Full Name:</dt>
            <dd class="col-sm-8"><?= $customer['full_name']; ?></dd>

            <dt class="col-sm-4">Email:</dt>
            <dd class="col-sm-8"><?= $customer['email']; ?></dd>

            <dt class="col-sm-4">Phone Number:</dt>
            <dd class="col-sm-8"><?= $customer['phone_number']; ?></dd>

            <dt class="col-sm-4">Address:</dt>
            <dd class="col-sm-8"><?= $customer['address']; ?></dd>

            <dt class="col-sm-4">City:</dt>
            <dd class="col-sm-8"><?= $customer['city']; ?></dd>

            <dt class="col-sm-4">State:</dt>
            <dd class="col-sm-8"><?= $customer['state']; ?></dd>

            <dt class="col-sm-4">Postal Code:</dt>
            <dd class="col-sm-8"><?= $customer['postal_code']; ?></dd>

            <dt class="col-sm-4">Country:</dt>
            <dd class="col-sm-8"><?= $customer['country']; ?></dd>
        </dl>
    </div>
</div>


<?php echo generateBookingsTableForCustomer($customer_id, $conn) ?>


<?php include 'footer.php'; ?>
