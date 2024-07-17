<?php  

function get_license_data(){
	
	global $wpdb;

	$where = '';
	$data  = array();
	$table = $wpdb->prefix . "license_management";

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

	$get_license_management_data = $wpdb->get_results(
		"SELECT * FROM $table AS lm
		$where 
		ORDER BY id DESC LIMIT ".$length." OFFSET ".$start
	,ARRAY_A);

	$recordsFiltered = $wpdb->get_var("SELECT COUNT(*)total FROM $table $where");
	$recordsTotal  = $wpdb->get_var("SELECT COUNT(*)total FROM $table");

	$orderIds = array();
	$multiple = false;
	foreach ( $get_license_management_data as $get_license_management_data_key => $license_management_data ) {

		if( !in_array($license_management_data['order_id'], $orderIds) ){
			$orderIds[] = $license_management_data['order_id'];
			$multiple = false;
		}else{
			$multiple = true;
		}

		if( $license_management_data['license_status'] == 'active' ){
			$license_status = '<span style="color:green;">'.$license_management_data['license_status'].'</span>';
		}else{
			$license_status = '<span style="color:red;">'.$license_management_data['license_status'].'</span>';
		}	

		if( !$multiple ){
			$send_license_email_button = '<button type="button" class="btn btn-primary btn-sm send_license_email" data-id="'.$license_management_data['order_id'].'">Send Email</button>';
		}else{
			$send_license_email_button = '';
		}

		$data[$get_license_management_data_key][0] = $license_management_data['id'];
		$data[$get_license_management_data_key][1] = $license_management_data['order_id'];
		//$data[$get_license_management_data_key][2] = $license_management_data['product_id'];
		$data[$get_license_management_data_key][2] = $license_management_data['full_name'];
		$data[$get_license_management_data_key][3] = $license_management_data['email'];
		$data[$get_license_management_data_key][4] = $license_management_data['company_name'];
		$data[$get_license_management_data_key][5] = $license_management_data['pc_id'];
		$data[$get_license_management_data_key][6] = $license_management_data['product_category'];
		$data[$get_license_management_data_key][7] = $license_management_data['nip'];
		$data[$get_license_management_data_key][8] = $license_management_data['license_key'];
		$data[$get_license_management_data_key][9] = $license_management_data['order_date'];
		$data[$get_license_management_data_key][10] = $license_management_data['activation_date'];
		$data[$get_license_management_data_key][11] = $license_management_data['order_status'];;
		$data[$get_license_management_data_key][12] = $license_status;
		$data[$get_license_management_data_key][13] = '<button type="button" class="btn btn-primary btn-sm edit-license mr-3 mb-1" data-id="'.$license_management_data['id'].'">EDIT</button>'.$send_license_email_button;
	}

	echo json_encode(array(
		'draw' 			  => (int) $_GET['draw'],
		'recordsFiltered' => (int) $recordsFiltered,
		'recordsTotal' 	  => (int) $recordsTotal,
		'data' 			  => $data
	));

	die;
}
add_action( 'wp_ajax_get_license_data', 'get_license_data' );
add_action( 'wp_ajax_nopriv_get_license_data', 'get_license_data' );