<?php

/**
 * Plugin Name: Ksiegowosc24 License Management
 * Description: A WordPress plugin for managing licenses for your products or services.
 * Version: 1.4
 * Author: Ksiegowosc24
 * Author URI: https://ksiegowosc24.pl/
 * Text Domain:k24
 */

//  Exit if accessed directly
if (!defined('ABSPATH')) exit;

// Activation hook for the plugin
register_activation_hook(__FILE__, 'k24_activate_plugin');
require_once plugin_dir_path(__FILE__) . 'plugin-activation.php';

// Register and render Admin pages
require_once plugin_dir_path(__FILE__) . 'register-admin-pages.php';
require_once plugin_dir_path(__FILE__) . 'pages/email-settings.php';
require_once plugin_dir_path(__FILE__) . 'pages/demo-expiration-email.php';
require_once plugin_dir_path(__FILE__) . 'pages/new-order-email.php';
require_once plugin_dir_path(__FILE__) . 'pages/license-management.php';
require_once plugin_dir_path(__FILE__) . 'pages/deactivated-license-email.php';
require_once plugin_dir_path(__FILE__) . 'pages/export-unsubscribers-emails.php';
require_once plugin_dir_path(__FILE__) . 'pages/email-sending-report.php';
require_once plugin_dir_path(__FILE__) . 'pages/send-custom-email.php';
require_once plugin_dir_path(__FILE__) . 'pages/export-license-emails.php';

// load static files for admin pages
require_once plugin_dir_path(__FILE__) . 'load-statics.php';

// Handle Ajax requests
require_once plugin_dir_path(__FILE__) . 'ajax/get-license-data.php';
require_once plugin_dir_path(__FILE__) . 'ajax/edit-license-data.php';
require_once plugin_dir_path(__FILE__) . 'ajax/regenerate-license-key.php';
require_once plugin_dir_path(__FILE__) . 'ajax/update-license-data.php';
require_once plugin_dir_path(__FILE__) . 'ajax/get-email-sending-report.php';
require_once plugin_dir_path(__FILE__) . 'ajax/custom-email-sending-progress.php';

// this filter function will be called in below files
function wpdocs_set_html_mail_content_type()
{
	return 'text/html';
}

// Send license with ajax when user clicks on send email button from license management page
require_once plugin_dir_path(__FILE__) . 'ajax/send-license-email.php';
require_once plugin_dir_path(__FILE__) . 'generate-license-key.php';
require_once plugin_dir_path(__FILE__) . 'set-license-data.php';
require_once plugin_dir_path(__FILE__) . 'so-status-completed.php';
require_once plugin_dir_path(__FILE__) . 'send-new-order-email.php';
require_once plugin_dir_path(__FILE__) . 'api/endpoints.php';
