<?php
/**
 * Export CSV for Custom Form Module
 *
 * @package CustomFormModule
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Export entries to CSV
 */
function cf_export_csv() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Unauthorized' );
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_form_entries';

    $entries = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY created_at DESC", ARRAY_A );

    if ( empty( $entries ) ) {
        wp_die( 'No entries to export' );
    }

    // Set headers for CSV download
    header( 'Content-Type: text/csv; charset=utf-8' );
    header( 'Content-Disposition: attachment; filename=custom-form-entries-' . date( 'Y-m-d' ) . '.csv' );

    $output = fopen( 'php://output', 'w' );

    // CSV headers
    fputcsv( $output, array( 'ID', 'Name', 'Email', 'Phone', 'Message', 'File URL', 'Created At' ) );

    // Data
    foreach ( $entries as $entry ) {
        fputcsv( $output, $entry );
    }

    fclose( $output );
    exit;
}

// Hook to a custom URL, e.g., ?cf_export_csv=1
if ( isset( $_GET['cf_export_csv'] ) && $_GET['cf_export_csv'] == '1' ) {
    add_action( 'init', 'cf_export_csv' );
}