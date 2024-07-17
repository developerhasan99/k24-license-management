<?php 

function edit_license_data(){

	global $wpdb;

	if ( ! wp_verify_nonce( $_POST['security'], 'security' ) ){
		wp_send_json_error(array(
			'message' => __('You can not perform this operation.','k24')
		));
	}

	$id = $_POST['id'];

	$table = $wpdb->prefix.'license_management';

	$license_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE id = %d", $id ), ARRAY_A );

	ob_start();
	?>

<div class="modal-header">
    <h5 class="modal-title" id="edit-license-modal">Edit license for: #<?php echo $license_data['order_id'] ?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="update_license_form" method="post">
        <div class="form-group row">
            <label for="license-key" class="col-sm-4 col-form-label">License Key:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="license-key" name="license-key"
                    value="<?php echo $license_data['license_key']; ?>">
                <button 
                    type="button" 
                    class="btn btn-primary" 
                    id="regenrate_license" 
                    data-nip="<?php echo $license_data['nip']; ?>"
                    data-category="<?php echo $license_data['product_category']; ?>"
                    data-activation="<?php echo $license_data['activation_date']; ?>"
                    style="margin: 10px 0;"
                >Regenrate License</button>
            </div>
        </div>
        <div class="form-group row">
            <label for="message-text" class="col-sm-4 col-form-label">License Status:</label>
            <div class="col-sm-8">
                <select id="license-status" name="license_status" class="form-control">
                    <option value="active"
                        <?php echo ( $license_data['license_status'] == 'active' ) ? 'selected' : '' ; ?>>Active
                    </option>
                    <option value="deactive"
                        <?php echo ( $license_data['license_status'] == 'deactive' ) ? 'selected' : '' ; ?>>Deactive
                    </option>
                </select>
            </div>
        </div>
        <div class="form-group row d-none">
            <label for="message-text" class="col-sm-4 col-form-label">Order Status:</label>
            <div class="col-sm-8">
                <select id="order-status" name="order_status" class="form-control">
                    <option value="pending"
                        <?php echo ( $license_data['order_status'] == 'pending' ) ? 'selected' : '' ; ?>>Oczekująca
                    </option>
                    <option value="failed"
                        <?php echo ( $license_data['order_status'] == 'failed' ) ? 'selected' : '' ; ?>>Nieudana
                    </option>
                    <option value="on-hold"
                        <?php echo ( $license_data['order_status'] == 'on-hold' ) ? 'selected' : '' ; ?>>Wstrzymane
                    </option>
                    <option value="processing"
                        <?php echo ( $license_data['order_status'] == 'processing' ) ? 'selected' : '' ; ?>>W trakcie
                        realizacji</option>
                    <option value="completed"
                        <?php echo ( $license_data['order_status'] == 'completed' ) ? 'selected' : '' ; ?>>Zrealizowane
                    </option>
                    <option value="refunded"
                        <?php echo ( $license_data['order_status'] == 'refunded' ) ? 'selected' : '' ; ?>>Zwrócone
                    </option>
                    <option value="cancelled"
                        <?php echo ( $license_data['order_status'] == 'cancelled' ) ? 'selected' : '' ; ?>>Anulowane
                    </option>
                </select>
            </div>
        </div>
        <!-- Hidden field for customer name -->
        <input type="hidden" id="customer-name" name="customer_name" value="<?php echo $license_data['full_name']; ?>">
        <input type="hidden" id="old-activation-date" name="old_activation_date" value="<?php echo $license_data['activation_date']; ?>">
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary" id="update_license" data-id="<?php echo $id; ?>">Update
        License</button>
</div>

<?php
	$html = ob_get_clean();

	wp_send_json_success(array(
		'html' => $html
	));
}

add_action( 'wp_ajax_edit_license_data', 'edit_license_data' );
add_action( 'wp_ajax_nopriv_edit_license_data', 'edit_license_data' );