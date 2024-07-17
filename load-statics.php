<?php 

function admin_enqueue_scripts_license_management(){

	$current_screen = get_current_screen();

	if( $current_screen->id == 'toplevel_page_license-management' || $current_screen->id == 'mange-license_page_email-sending-report' ){
		wp_enqueue_style( 'twitter-bootstrap-css', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css', '', time() );
		wp_enqueue_style( 'dataTables-bootstrap-css', 'https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css', '', time() );
		wp_enqueue_style( 'responsive-bootstrap-css', 'https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css', '', time() );
		wp_enqueue_style( 'sweetalert2-bootstrap-css', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css', '', time() );
		wp_enqueue_style( 'sweetalert2-css', 'https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.3/sweetalert2.min.css', '', time() );

		wp_enqueue_script( 'datatable-js', 'https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js', array( 'jquery' ), time(), false );
		wp_enqueue_script( 'datatable-bootstrap-js', 'https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js', array( 'jquery' ), time(), false );
		wp_enqueue_script( 'datatable-responsive-js', 'https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js', array( 'jquery' ), time(), false );
		wp_enqueue_script( 'datatable-responsive-bootstrap-js', 'https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap4.min.js', array( 'jquery' ), time(), false );
		wp_enqueue_script( 'popper-js', 'https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js', array( 'jquery' ), time(), false );
		wp_enqueue_script( 'bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js', array( 'jquery' ), time(), false );
		wp_enqueue_script( 'sweetalert2-js', 'https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.3/sweetalert2.min.js', array( 'jquery' ), time(), false );
	}
}
add_action( 'admin_enqueue_scripts', 'admin_enqueue_scripts_license_management', 10, 1 );