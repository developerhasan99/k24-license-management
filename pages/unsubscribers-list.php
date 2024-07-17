<?php 

function unsubscribers_list() {
    global $wpdb;
    $table = $wpdb->prefix . 'unsubscribers';

    $results = $wpdb->get_results("SELECT * FROM $table;");

    ?>
    <div class="wrap">
        <h1 class="text-bold my-3 h4">Unsubscribers list!</h1>
            <table id="unsubscribers-list" class="table table-striped table-bordered dt-responsive">
            <thead>
                <th>SL</th>
                <th>Email Address</th>
                <th>Service</th>
                <th>Date</th>
                <th>Ignoring</th>
                <th style="width: 150px;">Action</th>
            </thead>
            <tbody>
                <?php 
                $sl_number = 0;
                foreach($results as $result) { 
                    $sl_number++;
                ?>
                    <tr>
                        <td><?php echo $sl_number; ?></td>
                        <td><?php echo $result->email_address; ?></td>
                        <td><?php echo $result->service; ?></td>
                        <td><?php echo $result->date; ?></td>
                        <td><?php echo $result->paused ? "yes" : "no"; ?></td>
                        <td><button data-id="<?php echo $result->id; ?>" class="btn btn-small btn-danger mr-3 delete-btn">Delete</button><button data-id="<?php echo $result->id; ?>" class="btn btn-small btn-warning ignore-btn">Ignore</button></td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>

        <script>
            jQuery(document).ready(function ($) {
                // Initialize Data table
                $("#unsubscribers-list").DataTable();

                // Perform deletion
                $(".delete-btn").click(function() {

                    let currentId = this.dataset.id;

                    var isConfirmed = confirm("Do you really want to delete this unsubscriber?")

                    if (isConfirmed) {
                        $.ajax({
                            url: ajaxurl, // AJAX URL defined by WordPress
                            type: 'POST',
                            data: {
                                action: 'delete_unsubscriber', // Action hook registered in WordPress
                                id: currentId,
                            },
                            success: function(response) {
                                // Handle the response
                                console.log(response.data);
                            },
                            error: function(error) {
                                console.error('Error:', error);
                            }
                        });
                    }
                })
            });
        </script>
    </div>
    
    <?php 
}