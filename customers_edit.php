<?php
include 'config.php'; // Include your database connection file

// Check if customer_id is provided in the URL
if (isset($_GET['customer_id'])) {
    $customer_id = $_GET['customer_id'];

    // Fetch customer details from the database
    $query = "SELECT * FROM customers WHERE customer_id = $customer_id";
    $result = $conn->query($query);
    $customer = $result->fetch_assoc();

    // Check if the form is submitted for updating customer details
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Collect updated form data
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $postal_code = $_POST['postal_code'];
        $country = $_POST['country'];

        // Update data in the customers table
        $update_query = "UPDATE customers SET full_name='$full_name', email='$email', phone_number='$phone_number', address='$address', city='$city', state='$state', postal_code='$postal_code', country='$country' WHERE customer_id = $customer_id";
        $conn->query($update_query);

        // Redirect to customers.php after updating the customer
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
        <h2>Edit Customer</h2>
        <!-- Form for editing customer details -->
        <form action="customers_edit.php?customer_id=<?= $customer_id; ?>" method="post">
            <!-- Add form fields based on your table columns -->
            <div class="form-group">
                <label for="full_name">Full Name:</label>
                <input type="text" class="form-control" name="full_name" value="<?= $customer['full_name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" value="<?= $customer['email']; ?>">
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" class="form-control" name="phone_number" value="<?= $customer['phone_number']; ?>">
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" class="form-control" name="address" value="<?= $customer['address']; ?>">
            </div>
            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" class="form-control" name="city" value="<?= $customer['city']; ?>">
            </div>
            <div class="form-group">
                <label for="state">State:</label>
                <input type="text" class="form-control" name="state" value="<?= $customer['state']; ?>">
            </div>
            <div class="form-group">
                <label for="postal_code">Postal Code:</label>
                <input type="text" class="form-control" name="postal_code" value="<?= $customer['postal_code']; ?>">
            </div>
            <div class="form-group">
                <label for="country">Country:</label>
                <input type="text" class="form-control" name="country" value="<?= $customer['country']; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update Customer</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
