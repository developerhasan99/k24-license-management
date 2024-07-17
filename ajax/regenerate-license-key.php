<?php  

function regenrate_license_key(){

	if ( ! wp_verify_nonce( $_POST['security'], 'security' ) ){
		wp_send_json_error(array(
			'message' => 'You can not perform this operation.'
		));
	}

	$nip = $_POST['nip'];
	$product_category = $_POST['category'];
	$activation_date = $_POST['activation'];

	$product_key = 1;
	if ($product_category === 'STANDARD') {
		$product_key = 2;
	}
	if ($product_category === 'PRO') {
		$product_key = 3;
	}

	$license_date = '000000';
	if($activation_date !== '0000-00-00 00:00:00') {
		$date_interface = new DateTime($activation_date);
		$license_date = $date_interface->format('ymd');
	}

	$license_key = $nip . $product_key . $license_date;
    $license_key = implode( "-", str_split( $license_key, 4 ));

	wp_send_json_success(array(
		'message' => __( 'license key generate successfully.','minti-framework' ),
		'license_key' => $license_key
	));
}

add_action( 'wp_ajax_regenrate_license_key', 'regenrate_license_key' );
add_action( 'wp_ajax_nopriv_regenrate_license_key', 'regenrate_license_key' );