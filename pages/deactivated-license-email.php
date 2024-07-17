<?php 

function deactivated_license_email() { 

    $is_updated = false;

	if( isset( $_POST['deactivated_license_email_subject'] ) ){
		$deactivated_license_email_subject = $_POST['deactivated_license_email_subject'];
		$deactivated_license_email_body = $_POST['deactivated_license_email_body'];

		update_option( 'deactivated_license_email_subject', $deactivated_license_email_subject );
		update_option( 'deactivated_license_email_body',  stripslashes($deactivated_license_email_body) );

        $is_updated = true;
	}

	$deactivated_license_email_subject = get_option( 'deactivated_license_email_subject' );
	$deactivated_license_email_body = get_option( 'deactivated_license_email_body' );

    ?>
<div id="k24_lm_email_settings" class="wrap">
    <h1 class="wp-heading-inline">
        Edit Settings.
    </h1>
    <hr class="wp-header-end">
    <?php if($is_updated) echo '<div id="message" class="notice is-dismissible updated"><p><strong>Settings updated.</strong></p></div>'; ?>
    <form method="post">
        <h2>Deactivated License Email Contents</h2>
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th><label for="deactivated-license-email-subject">Email Subject</label></th>
                    <td>
                        <input 
                            type="text" 
                            id="deactivated-license-email-subject" 
                            name="deactivated_license_email_subject"
                            placeholder="Email subject"
                            style="width: 700px; max-width: 100%;"
                            value="<?php echo !empty( $deactivated_license_email_subject ) ? $deactivated_license_email_subject : '' ; ?>"
                        >
                    </td>
                </tr>
                <tr>
                    <th><label for="deactivated-license-email-body">Email Body</label></th>
                    <td>
                        <div style="width: 700px; max-width: 100%;">
                            <?php wp_editor( $deactivated_license_email_body, "deactivated-license-email-body", ['tinymce' => true, 'textarea_name' => 'deactivated_license_email_body'] ); ?>
                            <p><strong>Note:</strong> You can use {unsubscription_link} as a placeholder in email body. it will replace data dynamically.</p>
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
