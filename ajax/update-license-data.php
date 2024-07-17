<?php

function update_license_data()
{
	global $wpdb;
	$table = $wpdb->prefix . 'license_management';

	if (!wp_verify_nonce($_POST['security'], 'security')) {
		wp_send_json_error(array(
			'message' => __('You can not perform this operation.', 'minti')
		));
	}

	$id = $_POST['id'];
	$license_key = $_POST['license_key'];
	$license_status = $_POST['license_status'];
	$order_status = $_POST['order_status'];
	$customer_name = $_POST['customer_name'];
	$customer_email = $_POST['customer_email'];
	$old_activation_date = $_POST['old_activation_date'];

	if (empty($license_key)) {
		wp_send_json_error(array(
			'message' => __('Please enter license key.', 'minti')
		));
	}

	// Insert Update data to user
	$data = ['license_key' => $license_key, 'license_status' => $license_status, 'order_status' => $order_status];

	$where = ['id' => $id]; // NULL value in WHERE clause.
	$wpdb->update($table, $data, $where);

	// Send email to user
	if ($license_status === 'active') {
		$license_email_subject = get_option('license_email_subject');
		$license_from_email = get_option('license_from_email');
		$license_email_body = wpautop(get_option('license_email_body'), true);

		$license_email_body = str_replace('{username}', $customer_name, $license_email_body);
		$license_email_body = str_replace('{order_id}', $id, $license_email_body);
		$license_email_body = str_replace('{products_and_licenses}', $license_key, $license_email_body);

		add_filter('wp_mail_content_type', 'wpdocs_set_html_mail_content_type');

		$to = $customer_email;
		$subject = $license_email_subject;
		$body = $license_email_body;
		$headers[] = 'From: Ksiegowosc24.pl <' . $license_from_email . '>';

		$mail = wp_mail($to, $subject, $body, $headers);

		// Reset content-type to avoid conflicts -- https://core.trac.wordpress.org/ticket/23578
		remove_filter('wp_mail_content_type', 'wpdocs_set_html_mail_content_type');
	}

	// Send Success response
	wp_send_json_success(array(
		'message' => __('License data updated successfully.', 'minti-framework')
	));
}

add_action('wp_ajax_update_license_data', 'update_license_data');
add_action('wp_ajax_nopriv_update_license_data', 'update_license_data');
