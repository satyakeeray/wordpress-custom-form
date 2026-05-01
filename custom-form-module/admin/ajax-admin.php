<?php
/**
 * Admin AJAX Handlers for Custom Form Module
 *
 * @package CustomFormModule
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Get entries for DataTable
 */
function cf_get_entries_ajax() {
    // Check nonce
    if ( ! wp_verify_nonce( $_POST['nonce'], 'cf_admin_nonce' ) ) {
        wp_send_json_error( 'Invalid nonce' );
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_form_entries';

    // Parameters
    $draw = intval( $_POST['draw'] );
    $start = intval( $_POST['start'] );
    $length = intval( $_POST['length'] );
    $search = sanitize_text_field( $_POST['search']['value'] );
    $order_column = intval( $_POST['order'][0]['column'] );
    $order_dir = sanitize_text_field( $_POST['order'][0]['dir'] );

    // Columns for ordering
    $columns = array( 'id', 'name', 'email', 'phone', 'created_at' );
    $order_by = $columns[$order_column] ?? 'id';
    $order_dir = in_array( $order_dir, array( 'asc', 'desc' ) ) ? $order_dir : 'asc';

    // Base query
    $where = '';
    if ( ! empty( $search ) ) {
        $where = $wpdb->prepare(
            "WHERE name LIKE %s OR email LIKE %s OR phone LIKE %s",
            '%' . $wpdb->esc_like( $search ) . '%',
            '%' . $wpdb->esc_like( $search ) . '%',
            '%' . $wpdb->esc_like( $search ) . '%'
        );
    }

    // Total records
    $total_query = "SELECT COUNT(*) FROM $table_name";
    $records_total = $wpdb->get_var( $total_query );

    // Filtered records
    $filtered_query = "SELECT COUNT(*) FROM $table_name $where";
    $records_filtered = $wpdb->get_var( $filtered_query );

    // Data query
    $data_query = $wpdb->prepare(
        "SELECT id, name, email, phone, created_at FROM $table_name $where ORDER BY $order_by $order_dir LIMIT %d OFFSET %d",
        $length,
        $start
    );
    $results = $wpdb->get_results( $data_query );

    $data = array();
    foreach ( $results as $row ) {
        $actions = '<button class="cf-delete-btn" data-id="' . esc_attr( $row->id ) . '" title="Delete"><i class="fa fa-trash"></i></button>';
        
        // Add file download/open buttons if file exists
        if ( ! empty( $row->file_url ) ) {
            $actions .= ' <a href="' . esc_url( $row->file_url ) . '" class="cf-file-btn cf-download-btn" title="Download" download><i class="fa fa-download"></i></a>';
            $actions .= ' <a href="' . esc_url( $row->file_url ) . '" class="cf-file-btn cf-open-btn" title="Open" target="_blank"><i class="fa fa-external-link"></i></a>';
        }
        
        $data[] = array(
            '', // Expand button placeholder
            esc_html( $row->id ),
            esc_html( $row->name ),
            esc_html( $row->email ),
            esc_html( $row->phone ),
            esc_html( $row->created_at ),
            $actions
        );
    }

    wp_send_json( array(
        'draw' => $draw,
        'recordsTotal' => $records_total,
        'recordsFiltered' => $records_filtered,
        'data' => $data
    ) );
}
add_action( 'wp_ajax_cf_get_entries', 'cf_get_entries_ajax' );

/**
 * Get single entry details for expand
 */
function cf_get_entry_details_ajax() {
    // Check nonce
    if ( ! wp_verify_nonce( $_POST['nonce'], 'cf_admin_nonce' ) ) {
        wp_send_json_error( 'Invalid nonce' );
    }

    $id = intval( $_POST['id'] );

    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_form_entries';

    $entry = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $id ) );

    if ( ! $entry ) {
        wp_send_json_error( 'Entry not found' );
    }

    $details = array(
        'message' => esc_html( $entry->message ),
        'file_url' => esc_url( $entry->file_url )
    );

    wp_send_json_success( $details );
}
add_action( 'wp_ajax_cf_get_entry_details', 'cf_get_entry_details_ajax' );

/**
 * Delete single entry
 */
function cf_delete_entry_ajax() {
    // Check nonce
    if ( ! wp_verify_nonce( $_POST['nonce'], 'cf_admin_nonce' ) ) {
        wp_send_json_error( 'Invalid nonce' );
    }

    $id = intval( $_POST['id'] );

    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_form_entries';

    $result = $wpdb->delete( $table_name, array( 'id' => $id ), array( '%d' ) );

    if ( $result === false ) {
        wp_send_json_error( 'Failed to delete entry' );
    }

    wp_send_json_success( 'Entry deleted' );
}
add_action( 'wp_ajax_cf_delete_entry', 'cf_delete_entry_ajax' );

/**
 * Delete all entries
 */
function cf_delete_all_entries_ajax() {
    // Check nonce
    if ( ! wp_verify_nonce( $_POST['nonce'], 'cf_admin_nonce' ) ) {
        wp_send_json_error( 'Invalid nonce' );
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_form_entries';

    $result = $wpdb->query( "TRUNCATE TABLE $table_name" );

    if ( $result === false ) {
        wp_send_json_error( 'Failed to delete all entries' );
    }

    wp_send_json_success( 'All entries deleted' );
}
add_action( 'wp_ajax_cf_delete_all_entries', 'cf_delete_all_entries_ajax' );

/**
 * Export entries to CSV based on current filters
 */
function cf_export_csv_ajax() {
    // Check nonce
    if ( ! wp_verify_nonce( $_POST['nonce'], 'cf_admin_nonce' ) ) {
        wp_send_json_error( 'Invalid nonce' );
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_form_entries';

    // Get search and filter parameters
    $search = sanitize_text_field( $_POST['search'] ?? '' );

    // Base query
    $where = '';
    if ( ! empty( $search ) ) {
        $where = $wpdb->prepare(
            "WHERE name LIKE %s OR email LIKE %s OR phone LIKE %s",
            '%' . $wpdb->esc_like( $search ) . '%',
            '%' . $wpdb->esc_like( $search ) . '%',
            '%' . $wpdb->esc_like( $search ) . '%'
        );
    }

    $entries = $wpdb->get_results( "SELECT * FROM $table_name $where ORDER BY created_at DESC", ARRAY_A );

    if ( empty( $entries ) ) {
        wp_send_json_error( 'No entries to export' );
    }

    // Generate CSV content
    $csv_content = "ID,Name,Email,Phone,Message,File URL,Created At\n";
    foreach ( $entries as $entry ) {
        $csv_content .= '"' . implode( '","', array_map( 'addslashes', $entry ) ) . "\"\n";
    }

    wp_send_json_success( array( 'csv' => $csv_content ) );
}
add_action( 'wp_ajax_cf_export_csv', 'cf_export_csv_ajax' );