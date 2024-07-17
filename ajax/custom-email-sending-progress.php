<?php

add_action('wp_ajax_get_custom_email_sending_progress', 'get_custom_email_sending_progress');

function get_custom_email_sending_progress()
{
    // Get the option value
    $option_value = get_option('custom_email_sending_progress');

    // Return the value in JSON format
    wp_send_json_success($option_value);
}
