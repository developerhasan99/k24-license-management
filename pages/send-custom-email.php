<?php

function send_custom_email()
{
	$is_updated = false;

	if (isset($_POST['custom_manual_email_subject'])) {
		$custom_manual_email_subject = $_POST['custom_manual_email_subject'];
		$custom_manual_email_body = $_POST['custom_manual_email_body'];

		update_option('custom_manual_email_subject', $custom_manual_email_subject);
		update_option('custom_manual_email_body', stripslashes($custom_manual_email_body));

		$is_updated = true;
	}

	$custom_manual_email_subject = get_option('custom_manual_email_subject');
	$custom_manual_email_body = get_option('custom_manual_email_body');


	// Communicate with DB and custom logics
	global $wpdb;
	$k24_license_table = $wpdb->prefix . 'license_management';

	$unsubscribed_emails = $wpdb->get_col("SELECT email FROM unsubscriber_emails");

	$main_emails = $wpdb->get_col("SELECT email FROM rafsoft_and_cf7_emails");
	$emails_from_k24_license_table = $wpdb->get_col("SELECT email FROM $k24_license_table");

	$all_emails = array_unique(array_merge($main_emails, $emails_from_k24_license_table));
	$emails_to_send = array_diff($all_emails, $unsubscribed_emails);

	// 	Count Emails
	$total_emails_count = count($all_emails);
	$emails_to_be_send_count = count($emails_to_send);
	$unsubscribed_emails_count = $total_emails_count - $emails_to_be_send_count;

	// Check if the process is running or not
	$is_email_sending = get_option('custom_email_sending_progress')['sending'];

?>
	<style>
		.sending-emails {
			max-width: 920px;
			margin-top: 20px;
		}

		.progress {
			width: 100%;
			background-color: #fff;
			border-radius: 5px;
			overflow: hidden;
			box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
		}

		.progress-bar {
			height: 30px;
			background-color: #0dd315;
			/* Green */
			text-align: center;
			line-height: 30px;
			color: black;
			transition: width 0.4s ease;
		}
	</style>
	<div id="k24_lm_email_settings" class="wrap">
		<h1 class="wp-heading-inline">
			Send a custom email to your user.
		</h1>
		<hr class="wp-header-end">
		<?php if ($is_updated) echo '<div id="message" class="notice is-dismissible updated"><p><strong>Settings updated.</strong></p></div>'; ?>
		<form method="post">
			<h2>Custom Email Contents</h2>
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th><label for="new-order-email-subject">Email Subject</label></th>
						<td>
							<input type="text" id="new-order-email-subject" name="custom_manual_email_subject" placeholder="Email subject" style="width: 700px; max-width: 100%;" value="<?php echo !empty($custom_manual_email_subject) ? $custom_manual_email_subject : ''; ?>">
						</td>
					</tr>
					<tr>
						<th><label for="new-order-email-body">Email Body</label></th>
						<td>
							<div style="width: 700px; max-width: 100%;">
								<?php wp_editor($custom_manual_email_body, "new-order-email-body", ['tinymce' => true, 'textarea_name' => 'custom_manual_email_body']); ?>
							</div>
							<p>This fild support {unsubscription_link} short code.</p>
						</td>
					</tr>
				</tbody>
			</table>
			<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Update Settings"></p>
		</form>

		<!-- 	Test Custom Email	 -->
		<hr />
		<div style="margin-bottom: 2rem;">
			<h2 class="wp-heading-inline">
				Send test email
			</h2>
			<div style="margin-bottom: 0.75rem;">
				<input type="text" id="test-email-input" name="test-email-address" placeholder="target@host.com" style="width: 400px; max-width: 100%;" value="">
			</div>
			<button id="send-test-email-button" class="button button-primary">Send test email</button>
			<div id="success-message" style="display: none; color: green; margin-top: 0.5rem;">Email sent successfully!</div>
			<div id="loader" style="display: none;  margin-top: 0.5rem;">Sending test email...</div>
		</div>

		<hr />
		<h2>Email list summery:</h2>
		<p style="display: flex; gap: 3rem;">
			<strong>Total Email: <?php echo $total_emails_count; ?></strong>
			<strong>Unsubscribed Email: <?php echo $unsubscribed_emails_count; ?></strong>
			<strong>Email to be sent: <?php echo $emails_to_be_send_count; ?></strong>
		</p>
		<button style="display: <?php echo $is_email_sending ? 'none' : 'inline-block' ?>;" class="button button-primary send-emails-button">Send emails</button>
		<h1 id="current-job-is-done" style="display: none;">Current job is done!</h1>
		<div class="sending-emails" style="display: <?php echo $is_email_sending ? 'block' : 'none' ?>;">
			<p>
				<strong>Sending email to: <span id="current-email"><?php echo $emails_to_send[0]; ?></span></strong>
			</p>
			<div class="progress">
				<div class="progress-bar" style="width: 0%"><span id="email-count">1</span>/<?php echo $emails_to_be_send_count; ?></div>
			</div>
		</div>
	</div>
	<!-- Javascipts for send action and Heartbeat -->
	<script>
		jQuery(document).ready(function($) {

			let intervalId = null;

			// Send Custom Email
			$('#send-test-email-button').on('click', function(e) {
				e.preventDefault();

				var email = $('#test-email-input').val();
				var $button = $(this); // Store reference to the button

				if (!email) {
					alert('Please enter an email address.');
					return;
				}

				// Do Status Related tasks here
				$button.attr('disabled', true);
				$('#loader').show();
				$('#success-message').hide();

				$.ajax({
					url: 'https://ksiegowosc24.pl/mail/test-custom-email.php',
					type: 'POST',
					data: {
						email: email
					},
					success: function(response) {
						// Hide loader & Show success message
						$('#loader').hide();
						$('#success-message').show();
						$button.attr('disabled', false);
					},
					error: function(xhr, status, error) {
						// Hide loader & Show error
						$('#loader').hide();
						$button.attr('disabled', false);
						alert('An error occurred: ' + error);
					}
				});
			});

			// Email Sending Functionality
			var progressBar = $('.sending-emails');

			$('.send-emails-button').click(function(e) {
				e.preventDefault();

				// Replace the UI Element
				var sendEmailsBtn = $(this);
				sendEmailsBtn.hide();
				progressBar.show();

				// Start listening to progress
				intervalId = setInterval(listeningToEmailSendingJob, 3000);

				// Send the request to the endpoint
				$.ajax({
					url: 'https://ksiegowosc24.pl/mail/send-custom-email.php',
					type: 'GET'
				});

			});

			<?php echo $is_email_sending ? 'intervalId = setInterval(listeningToEmailSendingJob, 3000);' : '' ?>

			// Get update report
			function listeningToEmailSendingJob() {
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'get_custom_email_sending_progress'
					},
					success: function(response) {
						if (response.success) {

							if (!response.data.sending) {
								progressBar.hide();
								$('.send-emails-button').show();
								$('#current-job-is-done').show();

								if (intervalId) {
									clearInterval(intervalId);
								}
							}

							// Update current email
							$('#current-email').text(response.data.current);

							// Update email count
							var sentCount = response.data.sent;
							var totalCount = <?php echo $emails_to_be_send_count; ?>;
							$('#email-count').text(sentCount + 1); // Adding 1 to match index starting from 0

							// Update progress bar
							var progress = ((sentCount + 1) / totalCount) * 100; // Calculate percentage
							$('.progress-bar').css('width', progress + '%');

						} else {
							console.error('AJAX error:', response.data);
						}
					},
					error: function(error) {
						console.error('AJAX error:', error);
					}
				});
			}
		});
	</script>

<?php
}
?>