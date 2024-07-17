<?php 

add_action('woocommerce_order_status_changed', 'so_status_completed', 10, 3);

function so_status_completed($order_id, $old_status, $new_status){

	global $wpdb;
	$table = $wpdb->prefix .'license_management';

    $data = [ 'order_status' => $new_status ];

	if( $new_status === 'completed' ){
		$data['license_status'] = 'active';

		$license_data = $wpdb->get_row( " SELECT * FROM $table WHERE order_id = $order_id " );

		$old_date = new DateTime($license_data->activation_date);
		$old_date->modify('+1 year');
		$current_date = new DateTime();
		$date_to_insert = date('Y-m-d H:i:s');

		if($old_date > $current_date) {
			$date_to_insert = $old_date->format('Y-m-d H:i:s');
		}

		$data['activation_date'] = $date_to_insert;

		// Regenerate License key
		$old_license_key = preg_replace('/[^0-9]/', '', $license_data->license_key);
		$key_without_date = substr($old_license_key, 0, 11);

		// Make six digit date to add in new license key
		$date_interface = new DateTime($date_to_insert);
		$license_key = $key_without_date . $date_interface->format('ymd');
		$license_key = implode( "-", str_split( $license_key, 4 ));

		$data['license_key'] = $license_key;

		// Send email to user
		send_email_to_user($license_data);

	}

	// Update the License details in DB
	$where = [ 'order_id' => $order_id ]; // NULL value in WHERE clause.
	$wpdb->update( $table, $data, $where );
}


function send_email_to_user($license_data) {
	$license_email_subject = get_option( 'license_email_subject' );
	$license_from_email = get_option( 'license_from_email' );
	$license_email_body = wpautop( get_option( 'license_email_body' ), true );

	$license_email_body = str_replace('{username}', $license_data->full_name, $license_email_body );
	$license_email_body = str_replace('{order_id}', $license_data->order_id, $license_email_body );
	$license_email_body = str_replace('{products_and_licenses}', $license_data->license_key, $license_email_body );

	add_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );

    //sending mail to customer after order with license instruction from email setting in plugin wp license management
	$to = $license_data->email; 
	$subject = $license_email_subject;
	$body = $license_email_body;
	$headers[] = 'From: Ksiegowosc24.pl <'.$license_from_email.'>';

	$mail = wp_mail( $to, $subject, $body, $headers );

	// Reset content-type to avoid conflicts -- https://core.trac.wordpress.org/ticket/23578
	remove_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );
}