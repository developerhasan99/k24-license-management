<?php 

function handle_ajax_request() {
    // Get the ID from the AJAX request
    $id = isset($_POST['id']) ? sanitize_text_field($_POST['id']) : 0;

    global $wpdb; 
    $table = $wpdb->prefix . 'unsubscribers';

    $where_condition = array('id' => $id);

    // Execute the delete query
    $result = $wpdb->delete($table, $where_condition);

    if ($result) {
        wp_send_json_success( );
    } else {
        wp_send_json_error( );
    }
}

// Hook to add the AJAX endpoint
add_action('wp_ajax_delete_unsubscriber', 'handle_ajax_request');