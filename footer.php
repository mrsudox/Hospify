    </div> <!-- Close container -->

    <!-- Footer -->
    <footer class="mt-5">
        <p class="text-center">&copy; 2023 Hotel Management System</p>
    </footer>

    <!-- Custom JavaScript -->
    <script src="script.js"></script>
    <script type="text/javascript">
        
$(document).ready(function() {
    $('.select-search').select2();
});

$(document).ready(function() {
    // Function to calculate total cost
    function calculateTotalCost() {
        // Get values from input fields
        var room_id = $('#room_id').val();
        var check_in_date = $('#check_in_date').val();
        var check_out_date = $('#check_out_date').val();

        // Make AJAX request to api.php
        jQuery.ajax({
            url: 'api.php',
            type: 'GET',
            data: {
                type: 'room_cost',
                room_id: room_id,
                check_in_date: check_in_date,
                check_out_date: check_out_date
            },
            dataType: 'json',
            success: function(response) {
                // Check if the request was successful
                if (response.success) {
                    // Update total cost field with the calculated value
                    $('#total_cost').val(response.room_cost);
                } else {
                    // Display an error message and set total cost to 0
                    $('#total_cost').val(0);
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                // Display an error message and set total cost to 0
                $('#total_cost').val(0);
                alert('Error: Unable to process the request.');
            }
        });
    }

    
    // Attach change event listeners to relevant input fields
$('#room_id, #check_in_date, #check_out_date').on('change', function() {
    // Call the function to calculate total cost
    calculateTotalCost();
    console.log("calculateTotalCost --- Started");
});

});

new DataTable('table');
    </script>
</body>
</html>
