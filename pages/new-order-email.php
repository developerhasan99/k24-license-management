<?php 

function new_order_email() { 

    $is_updated = false;

	if( isset( $_POST['new_order_email_subject'] ) ){
		$new_order_email_subject = $_POST['new_order_email_subject'];
		$new_order_email_body = $_POST['new_order_email_body'];

		update_option( 'new_order_email_subject', $new_order_email_subject );
		update_option( 'new_order_email_body',  stripslashes($new_order_email_body) );

        $is_updated = true;
	}

	$new_order_email_subject = get_option( 'new_order_email_subject' );
	$new_order_email_body = get_option( 'new_order_email_body' );

    ?>
<div id="k24_lm_email_settings" class="wrap">
    <h1 class="wp-heading-inline">
        Edit Settings.
    </h1>
    <hr class="wp-header-end">
    <?php if($is_updated) echo '<div id="message" class="notice is-dismissible updated"><p><strong>Settings updated.</strong></p></div>'; ?>
    <form method="post">
        <h2>New Order Email Contents</h2>
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th><label for="new-order-email-subject">Email Subject</label></th>
                    <td>
                        <input 
                            type="text" 
                            id="new-order-email-subject" 
                            name="new_order_email_subject"
                            placeholder="Email subject"
                            style="width: 700px; max-width: 100%;"
                            value="<?php echo !empty( $new_order_email_subject ) ? $new_order_email_subject : '' ; ?>"
                        >
                    </td>
                </tr>
                <tr>
                    <th><label for="new-order-email-body">Email Body</label></th>
                    <td>
                        <div style="width: 700px; max-width: 100%;">
                            <?php wp_editor( $new_order_email_body, "new-order-email-body", ['tinymce' => true, 'textarea_name' => 'new_order_email_body'] ); ?>
                            <p><strong>Note:</strong> You can use {order_id}, {product_name}, {price_netto}, {price_brutto}, and {payment_link} as a placeholder in email body. it will replace data dynamically.</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Update Settings"></p>
    </form>
</div>

<?php

}
