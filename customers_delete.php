<?php
include 'config.php'; // Include your database connection file

// Check if customer_id is provided in the URL
if (isset($_GET['customer_id'])) {
    $customer_id = $_GET['customer_id'];

    // Delete customer from the database
    $delete_query = "DELETE FROM customers WHERE customer_id = $customer_id";
    $conn->query($delete_query);

    // Redirect to customers.php after deleting the customer
    header("Location: customers.php");
    exit();
} else {
    // Redirect to customers.php if customer_id is not provided
    header("Location: customers.php");
    exit();
}
?>
