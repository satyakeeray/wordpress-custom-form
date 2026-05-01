<?php
/**
 * Admin Menu for Custom Form Module
 *
 * @package CustomFormModule
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add admin menu
 */
function cf_add_admin_menu() {
    add_menu_page(
        'Custom Forms',           // Page title
        'Custom Forms',           // Menu title
        'manage_options',         // Capability
        'custom-forms',           // Menu slug
        'cf_admin_page_callback', // Callback function
        'dashicons-feedback',     // Icon
        30                        // Position
    );
}
add_action( 'admin_menu', 'cf_add_admin_menu' );