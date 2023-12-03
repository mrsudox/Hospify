<?php
include 'config.php'; // Include your database connection file

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postal_code = $_POST['postal_code'];
    $country = $_POST['country'];

    // Insert data into the customers table
    $query = "INSERT INTO customers (full_name, email, phone_number, address, city, state, postal_code, country) VALUES ('$full_name', '$email', '$phone_number', '$address', '$city', '$state', '$postal_code', '$country')";
    $conn->query($query);

    // Redirect to customers.php after adding a new customer
    header("Location: customers.php");
    exit();
}

?>

<?php include 'header.php'; ?>

<div class="row">
    <div class="col-md-6">
        <h2>Add New Customer</h2>
        <!-- Form for adding new customers -->
        <form action="customers_add.php" method="post">
            <!-- Add form fields based on your table columns -->
            <div class="form-group">
                <label for="full_name">Full Name:</label>
                <input type="text" class="form-control" name="full_name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email">
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" class="form-control" name="phone_number">
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" class="form-control" name="address">
            </div>
            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" class="form-control" name="city">
            </div>
            <div class="form-group">
                <label for="state">State:</label>
                <input type="text" class="form-control" name="state">
            </div>
            <div class="form-group">
                <label for="postal_code">Postal Code:</label>
                <input type="text" class="form-control" name="postal_code">
            </div>
            <div class="form-group">
                <label for="country">Country:</label>
                <input type="text" class="form-control" name="country">
            </div>
            <button type="submit" class="btn btn-primary">Add Customer</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
