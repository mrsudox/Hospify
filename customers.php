<?php
include 'config.php'; // Include your database connection file

// Fetch customers from the database
$query = "SELECT * FROM customers";
$result = $conn->query($query);

?>

<?php include 'header.php'; ?>

<!-- Display customers in a table -->
<div class="row">
    <div class="col-md-12">
        <h2>Customers List</h2>
        <a href="customers_add.php" class="btn btn-primary my-2"><i class="fa-solid fa-plus"></i> Add Customers</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Customer ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= $row['customer_id']; ?></td>
                        <td><a href="customers_profile.php?customer_id=<?= $row['customer_id']; ?>"><?= $row['full_name']; ?></a></td>
                        <td><?= $row['email']; ?></td>
                        <td><?= $row['phone_number']; ?></td>
                        <td>
                            <a title="Edit Booking Details" href="customers_edit.php?customer_id=<?= $row['customer_id']; ?>" class="btn btn-primary btn-sm"><i class="fa-regular fa-pen-to-square"></i></a>
                            <a title="Delete Booking (Cannot Restore)" href="customers_delete.php?customer_id=<?= $row['customer_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this customer?')"><i class="fa-solid fa-trash"></i></a>
                            <a title="View Booking Details" href="customers_profile.php?customer_id=<?= $row['customer_id']; ?>" class="btn btn-secondary btn-sm"><i class="fa-regular fa-eye"></i></a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
