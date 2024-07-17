<?php 

function send_new_order_email( $order_id ) {

    if ( ! $order_id ){
        return;
    }

    global $wpdb;
    $order_post = $wpdb->get_row("SELECT post_password, post_status FROM $wpdb->posts WHERE ID = $order_id");

    if ($order_post) {
        $order_key = $order_post->post_password;

        $email_subject = get_option('new_order_email_subject');
        $email_body = wpautop( get_option('new_order_email_body'), true );

        $order = new WC_Order( $order_id );

        $product_name = '';
        foreach($order->get_items() as $item) {
			$product_name = $item['name'];
        }

        $price_brutto = $order->get_total();

        // Get tax amount.
        $tax_amount = $order->total_tax;
        // Calculate order total without taxes.
        $price_netto = $price_brutto - $tax_amount;

        $payment_url = get_home_url( ) . '/zamowienie/order-pay/' . $order_id . '/?pay_for_order=true&key=' . $order_key;
		
		if( $order_post->post_status === 'wc-on-hold' ) {
            $payment_url = 'przelew bankowy';
        }

        $email_body = str_replace('{order_id}', $order_id, $email_body);
        $email_body = str_replace('{product_name}', $product_name, $email_body);
        $email_body = str_replace('{price_netto}', $price_netto, $email_body);
        $email_body = str_replace('{price_brutto}', $price_brutto, $email_body);
        $email_body = str_replace('{payment_link}', $payment_url, $email_body);

        add_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );

        $to = $order->billing_email; 
        // $to = 'sobujaliblogger@gmail.com'; 
        $headers[] = 'From: Ksiegowosc24.pl <biuro@rafsoft.net>';

        $mail = wp_mail( $to, $email_subject, $email_body, $headers );

        // Reset content-type to avoid conflicts -- https://core.trac.wordpress.org/ticket/23578
        remove_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );
        
    }

}

add_action('woocommerce_checkout_order_processed', 'send_new_order_email', 10, 1);
