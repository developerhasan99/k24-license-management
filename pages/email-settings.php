<?php 

function license_email_settings() { 

    $is_updated = false;

	if( isset( $_POST['license_email_subject'] ) ){
		$license_email_subject = $_POST['license_email_subject'];
		$license_from_email = $_POST['license_from_email'];
		$license_email_body = $_POST['license_email_body'];

		$reminder_email_subject = $_POST['reminder_email_subject'];
		$reminder_email_body = $_POST['reminder_email_body'];

		update_option( 'license_email_subject', $license_email_subject );
		update_option( 'license_from_email', $license_from_email );
		update_option( 'license_email_body', stripslashes($license_email_body) );
        
		update_option( 'reminder_email_subject', $reminder_email_subject );
		update_option( 'reminder_email_body', stripslashes($reminder_email_body) );

        $is_updated = true;
	}

	$license_email_subject = get_option( 'license_email_subject' );
	$license_from_email = get_option( 'license_from_email' );
	$license_email_body = get_option( 'license_email_body' );

    $reminder_email_subject = get_option('reminder_email_subject');
    $reminder_email_body = get_option( 'reminder_email_body' );

    ?>
<div id="k24_lm_email_settings" class="wrap">
    <h1 class="wp-heading-inline">
        Edit Settings.
    </h1>
    <hr class="wp-header-end">
    <?php if($is_updated) echo '<div id="message" class="notice is-dismissible updated"><p><strong>Settings updated.</strong></p></div>'; ?>
    <form method="post">
        <h2>License Email Contents</h2>
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th><label for="license-email-subject">Email Subject</label></th>
                    <td>
                        <input 
                            type="text" 
                            id="license-email-subject" 
                            name="license_email_subject"
                            placeholder="Email subject"
                            style="width: 700px; max-width: 100%;"
                            value="<?php echo !empty( $license_email_subject ) ? $license_email_subject : '' ; ?>"
                        >
                    </td>
                </tr>
                <tr>
                    <th><label for="license-from-email">From Email</label></th>
                    <td>
                        <input 
                            type="text" 
                            id="license-from-email" 
                            name="license_from_email"
                            placeholder="From email"
                            style="width: 700px; max-width: 100%;"
                            value="<?php echo !empty( $license_from_email ) ? $license_from_email : '' ; ?>"
                        >
                    </td>
                </tr>
                <tr>
                    <th><label for="license-email-body">Email Body</label></th>
                    <td>
                        <div style="width: 700px; max-width: 100%;">
                            <?php wp_editor( $license_email_body, "license-email-body", ['tinymce' => true, 'textarea_name' => 'license_email_body'] ); ?>
                            <p><strong>Note:</strong> You can use {username}, {order_id} {products_and_licenses} as a placeholder in email body. it will replace data dynamically.</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <hr />
        <h2>Unpaid reminder email contents</h2>
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th><label for="reminder-email-subject">Email Subject</label></th>
                    <td>
                        <input 
                            type="text" 
                            id="reminder-email-subject" 
                            name="reminder_email_subject"
                            placeholder="Email subject"
                            style="width: 700px; max-width: 100%;"
                            value="<?php echo !empty( $reminder_email_subject ) ? $reminder_email_subject : '' ; ?>"
                        >
                    </td>
                </tr>
                <tr>
                    <th><label for="reminder-email-body">Email Body</label></th>
                    <td>
                        <div style="width: 700px; max-width: 100%;">
                            <?php wp_editor( $reminder_email_body, "reminder-email-body", ['tinymce' => true, 'textarea_name' => 'reminder_email_body'] ); ?>
                            <p><strong>Note:</strong> You can use {order_id}, {product_name}, {price}, {payment_link}, {order_date}, and {unsubscription_link} as a placeholder in email body. it will replace data dynamically.</p>
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