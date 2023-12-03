<?php
include 'config.php'; // Include your database connection file

// Fetch rooms from the database
$query = "SELECT * FROM rooms";
$result = mysqli_query($conn, $query);

?>

<?php include 'header.php'; ?>

<!-- Display rooms in a table -->
<div class="row">
    <div class="col-md-12">
        <h2>Rooms List</h2>
        <a href="rooms_add.php" class="btn btn-primary my-2"> <i class="fa-solid fa-plus"></i> Add Room</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Room Number</th>
                    <th>Room Type</th>
                    <th>Capacity</th>
                    <th>Rate</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?= $row['room_number']; ?></td>
                        <td><?= $row['room_type']; ?></td>
                        <td><?= $row['capacity']; ?></td>
                        <td><?= $row['rate']; ?></td>
                        <td><?= $row['description']; ?></td>
                        <td>
                            <a title="Edit Booking Details"  href="rooms_edit.php?room_id=<?= $row['room_id']; ?>" class="btn btn-primary btn-sm"><i class="fa-regular fa-pen-to-square"></i></a>
                            <a title="Delete Booking (Cannot Restore)" href="rooms_delete.php?room_id=<?= $row['room_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this room?')"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
