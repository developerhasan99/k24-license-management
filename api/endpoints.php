<?php

// Register the endpoint
function custom_json_endpoint() {
    register_rest_route('license-management/v1', '/validate', array(
        'methods' => 'POST',
        'callback' => 'k24_validate_license_key',
    ));
}
add_action('rest_api_init', 'custom_json_endpoint');

// Define the callback function
function k24_validate_license_key($request) {
    $data = $request->get_json_params();

    if (empty($data)) {
        return [
            'success' => false,
            'message' => 'No data provided'
        ];
    }

    // Retrieve data from the JSON request
    $email_address = $data['email_address'];
    $product_category = $data['product_category'];
	$nip = preg_replace('/[^0-9]/', '', $data['nip']);

    if (strlen($nip) !== 10) {
        return [
            'success' => false,
            'message' => 'Invalid NIP provided'
        ];
    }

    $response = [
        'success' => true,
        'license_status' => 'DEMO',
		'activation_date' => date('Y-m-d H:i:s'),
    ];

    global $wpdb;
	$table = $wpdb->prefix.'license_management';

    $license_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE nip = %s AND product_category = %s", $nip, $product_category));

	if ($license_data) {
        $response['activation_date'] = $license_data->activation_date;
        $response['license_status'] = $license_data->license_status;

        if ($license_data->license_status === 'active') {

            $expiration_date = new DateTime($license_data->activation_date);
            $expiration_date->modify('+1 year');
            $current_date = new DateTime();
            
            if($expiration_date < $current_date) {
                $response['license_status'] = 'deactive';
                $wpdb->update($table, ['license_status' => 'deactive'], ['id' => $license_data->id]);
            }
        }

    } else {
        $data = [
            'product_category' => $product_category,
            'nip' => $nip,
            'email' => $email_address,
            'license_status' => 'DEMO',
            'activation_date' => date('Y-m-d H:i:s'),
        ];
        $wpdb->insert($table, $data);
    }

    return $response;
}