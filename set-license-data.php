<?php

function set_license_data( $order_id ) {

    if ( ! $order_id ){
        return;
    }

    // Allow code execution only once 
    if( ! get_post_meta( $order_id, '_woocommerce_checkout_order_processed_action_done', true ) ) {

		global $wpdb;
		$table = $wpdb->prefix.'license_management';

        // Get an instance of the WC_Order object
        $order = new WC_Order( $order_id );

        $user_id   	       = $order->user_id; // Get the costumer ID
        $order_key 	       = $order->order_number;
        $date_created      = $order->order_date;
        $order_status      = $order->status;
        $billing_tax_number = $order->billing_tax_number;
		$billing_tax_number = preg_replace('/[^0-9]/', '', $billing_tax_number);
		
		// Note: Set Billing_address_2 from billing_tax_number for api response, since the customized billing_tax_number is not present in API response, we must use billing_address as NIP filed
		update_post_meta( $order_id, '_billing_address_2', $billing_tax_number );

		foreach($order->get_items() as $item) {

			$product_id   = $item["product_id"];
			$product_name = $item['name'];

			// Determine Product Category
			$product_key = 1;
			$product_category = 'START';
			if ($product_id == 569 || $product_id == 486) {
				$product_key = 2;
				$product_category = 'STANDARD';
			}
			if ($product_id == 570 || $product_id == 489) {
				$product_key = 3;
				$product_category = 'PRO';
			}

			$old_order = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE nip = %s AND product_category = %s", $billing_tax_number, $product_category));

			if ($old_order) {

				// Just update the order Id and return and wait for the Order Completion, the update activation date from there
				$wpdb->update($table, ['order_id' => $order_id], ['id' => $old_order->id] );
				return;

			} else {

				// Generate License Key using generate_license_key() function
				// if ( strlen($billing_tax_number) > 10 ) {
				// 	$remove_numbers = strlen($billing_tax_number) - 10;
				// 	$billing_tax_number = substr($billing_tax_number, 0, -$remove_numbers);
				// }
				// $randomLength = 16 - strlen($billing_tax_number);
				// $license_key = $billing_tax_number . generate_license_key( $randomLength, false, false, '0123456789' );

				$license_key = $billing_tax_number . $product_key . '000000';

				$license_key = implode( "-", str_split( $license_key, 4 ));

				$data = array(
					'order_id' 		  	=> $order_id,
					'product_id' 	  	=> $product_id,
					'user_id' 		  	=> $user_id,
					'pc_id' 		  	=> '',
					'product_name' 	  	=> $product_name,
					'product_category' 	=> $product_category,
					'email' 	  	  	=> $order->billing_email,
					'full_name' 	  	=> $order->billing_first_name . ' ' . $order->billing_last_name,
					'company_name' 	  	=> $order->billing_company,
					'nip' 	  		  	=> $billing_tax_number,
					'license_key' 	  	=> $license_key,
					'order_date' 	  	=> $date_created,
					// 'activation_date' 	=> '0000-00-00 00:00:00',
					'order_status' 	  	=> $order_status,
					'license_status'  	=> 'deactive'
				);
			
				$wpdb->insert( $table, $data );
			}

		}
		
        // Flag the action as done (to avoid repetitions on reload for example)
        update_post_meta( $order_id, '_woocommerce_checkout_order_processed_action_done', true );
    }
}

//add_action('woocommerce_thankyou', 'set_license_data', 10, 1);
add_action('woocommerce_checkout_order_processed', 'set_license_data', 10, 1);