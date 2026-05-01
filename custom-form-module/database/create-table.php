<?php
/**
 * Database Table Creation for Custom Form Module
 *
 * @package CustomFormModule
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Create the custom form entries table
 */
function cf_create_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'custom_form_entries';

    // Check if table exists
    if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            phone varchar(20) DEFAULT '' NOT NULL,
            message text NOT NULL,
            file_url varchar(500) DEFAULT '' NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );
    }
}

// Run on init
add_action( 'init', 'cf_create_table' );