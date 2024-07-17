<?php 

function send_license_email(){
	global $wpdb;

	if ( ! wp_verify_nonce( $_POST['security'], 'security' ) ){
		wp_send_json_error(array(
			'message' => __('You can not perform this operation.','minti')
		));
	}

	$id = $_POST['id'];

	$table = $wpdb->prefix.'license_management';

	$license_data = $wpdb->get_results( " SELECT * FROM $table WHERE order_id = $id ",  ARRAY_A );

	$license_email_subject = get_option( 'license_email_subject' );
	$license_from_email = get_option( 'license_from_email' );
	$license_email_body = wpautop( get_option( 'license_email_body' ), true );

	$productIds = array();
	$multiple = false;

	$key_number = 1;
	foreach ( $license_data  as $key => $data ) {

		if( !in_array($data['product_id'], $productIds) ){
			$productIds[] = $data['product_id'];
			$multiple = false;
			$key_number = 1;
			$products_and_licenses .= '<br/>';
		}else{
			$multiple = true;
		}

		if( !$multiple ){
			$products_and_licenses .= $data['product_name']."<br/>";
		}

		$products_and_licenses .= $key_number.'. '.$data['license_key']."<br/>";

		$key_number++;
	}

	$license_email_body = str_replace('{username}', $license_data[0]['full_name'], $license_email_body );
	$license_email_body = str_replace('{order_id}', $license_data[0]['order_id'], $license_email_body );
	$license_email_body = str_replace('{products_and_licenses}', $products_and_licenses, $license_email_body );

	if( empty( $license_from_email ) ){
		wp_send_json_error(array(
			'message' => __('Please set from email address in settings page.','minti')
		));
	}

	add_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );

	$to = $license_data[0]['email'];
	$subject = $license_email_subject;
	$body = $license_email_body;
	$headers[] = 'From: Ksiegowosc24.pl <'.$license_from_email.'>';

	$mail = wp_mail( $to, $subject, $body, $headers );

	// Reset content-type to avoid conflicts -- https://core.trac.wordpress.org/ticket/23578
	remove_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );

	if( $mail ){
		wp_send_json_success(array(
			'message' => __( 'Email sent successfully.','k24' )
		));
	}

	wp_send_json_error(array(
		'message' => __( 'Something went wrong please try later.','k24' )
	));
}

add_action( 'wp_ajax_send_license_email', 'send_license_email' );
add_action( 'wp_ajax_nopriv_send_license_email', 'send_license_email' );