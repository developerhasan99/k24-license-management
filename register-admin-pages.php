<?php

function k24_register_my_custom_menu_page()
{
    add_menu_page(
        __('License Management', 'k24'),
        'Mange License',
        'manage_options',
        'license-management',
        'license_management',
        'dashicons-awards',
        6
    );

    add_submenu_page(
        'license-management',
        __('Email settings', 'k24'),
        __('Email settings', 'k24'),
        'manage_options',
        'license-email-settings',
        'license_email_settings'
    );
    add_submenu_page(
        'license-management',
        __('Demo expiration', 'k24'),
        __('Demo expiration', 'k24'),
        'manage_options',
        'demo-expiration-email',
        'demo_expiration_email'
    );
    add_submenu_page(
        'license-management',
        __('New order', 'k24'),
        __('New order', 'k24'),
        'manage_options',
        'new-order-email',
        'new_order_email'
    );
    add_submenu_page(
        'license-management',
        __('Deactivated license', 'k24'),
        __('Deactivated license', 'k24'),
        'manage_options',
        'deactivated-license-email',
        'deactivated_license_email'
    );
    add_submenu_page(
        'license-management',
        __('Export license emails', 'k24'),
        __('Export license emails', 'k24'),
        'manage_options',
        'export-license-emails',
        'export_license_emails'
    );
    add_submenu_page(
        'license-management',
        __('Export unsubscribers', 'k24'),
        __('Export unsubscribers', 'k24'),
        'manage_options',
        'export-unsubscribers-emails',
        'export_unsubscribers_emails'
    );

    add_submenu_page(
        'license-management',
        __('Email sending report', 'k24'),
        __('Email sending report', 'k24'),
        'manage_options',
        'email-sending-report',
        'email_sending_report'
    );

    add_submenu_page(
        'license-management',
        __('Send Custom Email', 'k24'),
        __('Send Custom Email', 'k24'),
        'manage_options',
        'send-custom-email',
        'send_custom_email'
    );
}
add_action('admin_menu', 'k24_register_my_custom_menu_page');
