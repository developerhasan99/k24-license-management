<?php 

function k24_activate_plugin() {
    // Create the database table
    global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();

	$table_name = $wpdb->prefix . "license_management"; 
	$table_name2 = $wpdb->prefix . "email_sending_report"; 

	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id int(20) NOT NULL AUTO_INCREMENT,
		order_id int(20) NOT NULL,
		product_id int(20) NOT NULL,
		user_id int(20) NULL,
		pc_id varchar(50) NULL,
		product_name text NOT NULL,
		product_category varchar(20) NOT NULL,
		email varchar(100) NOT NULL,
		full_name varchar(50) NOT NULL,
		company_name text NOT NULL,
		nip varchar(50) NOT NULL,
		license_key tinytext NOT NULL,
		order_date varchar(20) DEFAULT '0000-00-00 00:00:00' NOT NULL,
		activation_date varchar(20) DEFAULT '0000-00-00 00:00:00' NULL,
		order_status varchar(20) NOT NULL,
		license_status varchar(20) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	$sql2 = "CREATE TABLE IF NOT EXISTS $table_name2 (
		id int(20) NOT NULL AUTO_INCREMENT,
		date_time varchar(20) NOT NULL,
		email_sent text NULL,
		email_ignored text NULL,
		script varchar(100) NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	dbDelta( $sql2 );
}