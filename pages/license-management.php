<?php 

function license_management(){

	?>
<style type="text/css">
div#license_management_wrapper {
    width: 95%;
    margin: 20px auto;
}

table.table-bordered.dataTable th,
table.table-bordered.dataTable td {
    border-left-width: 0;
    padding: 5px 10px;
    font-size: 14px;
    vertical-align: middle;
}

div.dataTables_wrapper div.dataTables_filter input {
    width: 300px !important;
}

div#edit-license-modal {
    top: 100px;
}

.modal-loader {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
}

.modal-loader .spinner-border {
    margin-right: 5px;
}

.swal2-container {
    margin-top: 50px !important;
}
</style>
<table id="license_management" class="table table-striped table-bordered dt-responsive" style="width:100%">
    <thead>
        <th>ID</th>
        <th>Order ID</th>
        <th>User Name</th>
        <th>Email</th>
        <th>Company</th>
        <th>PC ID</th>
        <th>Product Category</th>
        <th>NIP</th>
        <th>License Key</th>
        <th>Order Date</th>
        <th>Activation Date</th>
        <th>Order Status</th>
        <th>License Status</th>
        <th>Action</th>
    </thead>
    <tbody>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
    </tbody>
</table>

<!-- Modal -->
<div class="modal fade" id="edit-license-modal" tabindex="-1" role="dialog" aria-labelledby="edit-license-modal"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        </div>
    </div>
</div>

<script type="text/javascript">
(function($) {

    $(document).ready(function() {

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        var license_management = $('#license_management').DataTable({
            "autoWidth": false,
            "pageLength": 50,
            "responsive": true,
            "ordering": false,
            "processing": true,
            "serverSide": true,
            "scrollY": "650px",
            "scrollCollapse": true,
            "ajax": {
                "url": '<?php echo admin_url('admin-ajax.php'); ?>',
                "data": function(d) {
                    d.action = 'get_license_data'
                }
            },
            "columns": [{
                    "width": "4%"
                }, // ID
                {
                    "width": "5%"
                }, // Order ID	
                {
                    "width": "10%"
                }, // User Name
                {
                    "width": "10%"
                }, // User Email
                {
                    "width": "10%"
                }, // User Company
                {
                    "width": "5%"
                }, // PC ID
                {
                    "width": "19%"
                }, // Product Name
                {
                    "width": "5%"
                }, // NIP
                {
                    "width": "10%"
                }, // License Key
                {
                    "width": "10%"
                }, // Order Date
                {
                    "width": "10%"
                }, // Activation Date
                {
                    "width": "5%"
                }, // Order Status
                {
                    "width": "5%"
                }, // License Status
                {
                    "width": "12%"
                } //Action
            ]
        });

        license_management.columns.adjust().draw();

        $(document).on('click', '.edit-license', function(e) {

            e.preventDefault();

            $('#edit-license-modal').modal('toggle');

            $('#edit-license-modal .modal-content').html(
                '<div class="modal-loader"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  Loading...<div>'
            ).prop('disabled', true);

            $.ajax({
                type: "POST",
                dataType: "json",
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: {
                    action: 'edit_license_data',
                    id: $(this).data('id'),
                    security: '<?php echo wp_create_nonce('security'); ?>'
                },
                success: function(response) {
                    $('#edit-license-modal .modal-content').html(response.data.html);
                },
                error: function(request, status, error) {
                    console.log(request.responseText);
                }
            });
        });

        $(document).on('click', '#regenrate_license', function(e) {

            e.preventDefault();

            $('#regenrate_license').html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  Regenerating License...'
            ).prop('disabled', true);
            $('#update_license').prop('disabled', true);
            $('#license-key').prop('disabled', true);
            $.ajax({
                type: "POST",
                dataType: "json",
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: {
                    action: 'regenrate_license_key',
                    nip: $(this).data('nip'),
                    category: $(this).data('category'),
                    activation: $(this).data('activation'),
                    security: '<?php echo wp_create_nonce('security'); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        Toast.fire({
                            icon: 'success',
                            title: response.data.message
                        });
                        $('#license-key').val(response.data.license_key);
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: response.data.message
                        });
                    }
                    $('#regenrate_license').html('Regenrate License').prop('disabled',
                        false);
                    $('#update_license').prop('disabled', false);
                    $('#license-key').prop('disabled', false);
                },
                error: function(request, status, error) {
                    console.log(request.responseText);
                    $('#regenrate_license').html('Regenrate License').prop('disabled',
                        false);
                    $('#update_license').prop('disabled', false);
                    $('#license-key').prop('disabled', false);
                }
            });
        });

        $(document).on('click', '#update_license', function(e) {

            e.preventDefault();

            $('#update_license').html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  Updating License...'
            ).prop('disabled', true);
            $('#regenrate_license').prop('disabled', true);
            $('#license-key').prop('disabled', true);
            $.ajax({
                type: "POST",
                dataType: "json",
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: {
                    action: 'update_license_data',
                    id: $(this).data('id'),
                    license_key: $('#license-key').val(),
                    license_status: $('#license-status').val(),
                    order_status: $('#order-status').val(),
                    customer_name: $('#customer-name').val(),
                    old_activation_date: $('#old-activation-date').val(),
                    security: '<?php echo wp_create_nonce('security'); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        license_management.ajax.reload(null, false);

                        //$("#license_management").DataTable().ajax.reload();
                        Toast.fire({
                            icon: 'success',
                            title: response.data.message
                        });
                        $('#edit-license-modal').modal('toggle');
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: response.data.message
                        });
                    }
                    $('#update_license').html('Update License').prop('disabled', false);
                    $('#regenrate_license').prop('disabled', false);
                    $('#license-key').prop('disabled', false);
                },
                error: function(request, status, error) {
                    console.log(request.responseText);
                    $('#update_license').html('Update License').prop('disabled', false);
                    $('#regenrate_license').prop('disabled', false);
                    $('#license-key').prop('disabled', false);
                }
            });
        });

        $(document).on('click', '.send_license_email', function(e) {

            e.preventDefault();

            $this = $(this);

            $this.html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  Sending...'
            ).prop('disabled', true);

            $.ajax({
                type: "POST",
                dataType: "json",
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: {
                    action: 'send_license_email',
                    id: $(this).data('id'),
                    security: '<?php echo wp_create_nonce('security'); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        Toast.fire({
                            icon: 'success',
                            title: response.data.message
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: response.data.message
                        });
                    }
                    $this.html('Send Email').prop('disabled', false);
                },
                error: function(request, status, error) {
                    console.log(request.responseText);
                    $this.html('Send Email').prop('disabled', false);
                }
            });
        });

    });
})(jQuery);
</script>
<?php

}