# Ksiegowosc24 License Management

A WordPress plugin for managing license issuance, tracking, and email notifications for WooCommerce orders.

## Overview

This plugin creates an admin dashboard for managing license records, sending license emails, configuring notification templates, exporting data, and tracking email delivery status.

It integrates with WooCommerce order processing to:
- store license details when orders are processed
- regenerate license keys automatically when order status changes
- send license emails on order completion
- send custom and status-related email notifications

## Key Features

- Admin license management table with server-side search and actions
- Edit license details and regenerate license keys
- Email templates for:
  - order confirmation
  - license delivery
  - demo expiration
  - deactivated licenses
  - custom email sends
- Export license emails and unsubscribed contacts
- Email sending report tracking
- WooCommerce integration via checkout and order status hooks

## Installation

1. Copy the plugin folder into `wp-content/plugins/`
2. Activate the plugin from the WordPress admin dashboard
3. Configure email templates and notification settings under the new `License Management` menu

## Admin Pages

The plugin adds a top-level admin menu named `License Management` with the following pages:

- `License Management` — view and manage stored license records
- `Email settings` — configure license email subject/body and sender address
- `Demo expiration` — configure demo expiration email content
- `New order` — configure new order email content
- `Deactivated license` — configure deactivation email content
- `Export license emails` — export customer license email addresses
- `Export unsubscribers` — export unsubscribed contacts
- `Email sending report` — review email sending history and counts
- `Send Custom Email` — send ad hoc emails to customers

## Database Tables

On activation the plugin creates two tables:

- `wp_license_management` — stores license records and order metadata
- `wp_email_sending_report` — stores report rows for email send activity

## Email Shortcodes

The plugin supports placeholder replacements in email templates:

- `{username}` — customer name
- `{order_id}` — WooCommerce order ID
- `{products_and_licenses}` — licence key details
- `{product_name}` — product name
- `{price_netto}` — order total net amount
- `{price_brutto}` — order total gross amount
- `{payment_link}` — order payment link for customers

## Requirements

- WordPress
- WooCommerce
- PHP compatible with your WordPress installation

## Notes

- The plugin uses AJAX and DataTables in the admin UI
- License keys are regenerated automatically when an order moves to the `completed` status
- Emails are sent using `wp_mail()` and the plugin applies HTML content type filters temporarily

## Author

Ksiegowosc24
