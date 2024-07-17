<?php  

function get_email_sending_report(){
	
	global $wpdb;

	$where = '';
	$data  = array();
	$table = $wpdb->prefix . "email_sending_report";

	$search    = $_GET['search']['value'];
	$length    = $_GET['length'];
	$start     = $_GET['start'];

	if( $search != '' ){
		$where = "
			WHERE 
			email LIKE '%$search%' OR 
			full_name LIKE '%$search%' OR 
			company_name LIKE '%$search%' OR 
			nip LIKE '%$search%'
		";
	}

	$get_email_sending_report_data = $wpdb->get_results(
		"SELECT * FROM $table AS lm
		$where 
		ORDER BY id DESC LIMIT ".$length." OFFSET ".$start
	,ARRAY_A);

	$recordsFiltered = $wpdb->get_var("SELECT COUNT(*)total FROM $table $where");
	$recordsTotal  = $wpdb->get_var("SELECT COUNT(*)total FROM $table");

	$orderIds = array();
	$multiple = false;
	foreach ( $get_email_sending_report_data as $get_email_sending_report_data_key => $email_sending_report_data ) {

		$email_sent = count(unserialize($email_sending_report_data['email_sent']));
		$email_ignored = count(unserialize($email_sending_report_data['email_ignored']));

		$data[$get_email_sending_report_data_key][0] = $email_sending_report_data['id'];
		$data[$get_email_sending_report_data_key][1] = $email_sending_report_data['date_time'];
		$data[$get_email_sending_report_data_key][2] = $email_sent;
		$data[$get_email_sending_report_data_key][3] = $email_ignored;
		$data[$get_email_sending_report_data_key][4] = $email_sending_report_data['script'];
		$data[$get_email_sending_report_data_key][5] = '<button type="button" class="btn btn-primary btn-sm view_full_report" data-id="'.$email_sending_report_data['id'].'">View Complete Report</button>';
	}

	echo json_encode(array(
		'draw' 			  => (int) $_GET['draw'],
		'recordsFiltered' => (int) $recordsFiltered,
		'recordsTotal' 	  => (int) $recordsTotal,
		'data' 			  => $data
	));

	die;
}
add_action( 'wp_ajax_get_email_sending_report', 'get_email_sending_report' );
add_action( 'wp_ajax_nopriv_get_email_sending_report', 'get_email_sending_report' );


function get_email_sending_full_report() {

	global $wpdb;

	if ( ! wp_verify_nonce( $_POST['security'], 'security' ) ){
		wp_send_json_error(array(
			'message' => __('You can not perform this operation.','k24')
		));
	}

	$id = $_POST['id'];

	$table = $wpdb->prefix.'email_sending_report';
	$sending_report = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE id = %d", $id ), ARRAY_A );

	$successfully_sent = unserialize($sending_report['email_sent']);
	$unsubscribers_ignored = unserialize($sending_report['email_ignored']);

	ob_start();
	?>
	<div class="modal-header">
		<h5 class="modal-title" id="edit-license-modal">Email sending full report: #<?php echo $sending_report['id'] ?></h5>
	</div>
	<div class="modal-body">
		<h4>Executed Script:</h4>
		<p><?php echo $sending_report['script'] ?></p>
		<h4>Execution Date:</h4>
		<p><?php echo $sending_report['date_time'] ?></p>
		<h4>Successfully Sent:</h4>
		<ol>
			<?php 
			foreach($successfully_sent as $email) {
				echo '<li>' . $email . '</li>';
			}
			?>
		</ol>
		<h4>Unsubscriber, Ignored:</h4>
		<ol>
		<?php 
			foreach($unsubscribers_ignored as $email) {
				echo '<li>' . $email . '</li>';
			}
			?>
		</ol>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
	</div>
	<?php
	$html = ob_get_clean();

	wp_send_json_success(array(
		'html' => $html
	));
}

add_action( 'wp_ajax_get_email_sending_full_report', 'get_email_sending_full_report' );
add_action( 'wp_ajax_nopriv_get_email_sending_full_report', 'get_email_sending_full_report' );