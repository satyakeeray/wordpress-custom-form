<?php
/**
 * Frontend AJAX Handler for Form Submission
 *
 * @package CustomFormModule
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Verify Google reCaptcha
 */
function cf_verify_recaptcha( $token ) {
    if ( defined( 'CF_BYPASS_RECAPTCHA' ) && CF_BYPASS_RECAPTCHA ) {
        return true;
    }

    if ( ! defined( 'CF_RECAPTCHA_SECRET_KEY' ) || empty( CF_RECAPTCHA_SECRET_KEY ) ) {
        return false;
    }

    $response = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify', array(
        'body' => array(
            'secret'   => CF_RECAPTCHA_SECRET_KEY,
            'response' => $token,
        ),
    ) );

    if ( is_wp_error( $response ) ) {
        return false;
    }

    $body = wp_remote_retrieve_body( $response );
    $data = json_decode( $body, true );

    return ! empty( $data['success'] ) && $data['success'] === true;
}

/**
 * Handle form submission via AJAX
 */
function cf_submit_form_ajax() {
    // Check nonce
    if ( ! wp_verify_nonce( $_POST['nonce'], 'cf_submit_form_nonce' ) ) {
        wp_send_json_error( 'Invalid nonce' );
    }

    // Verify reCaptcha
    if ( ( ! defined( 'CF_BYPASS_RECAPTCHA' ) || ! CF_BYPASS_RECAPTCHA ) ) {
        $recaptcha_token = isset( $_POST['g-recaptcha-response'] ) ? sanitize_text_field( $_POST['g-recaptcha-response'] ) : '';
        if ( empty( $recaptcha_token ) || ! cf_verify_recaptcha( $recaptcha_token ) ) {
            wp_send_json_error( 'reCaptcha verification failed' );
        }
    }

    // Sanitize inputs
    $name    = sanitize_text_field( $_POST['name'] ?? '' );
    $email   = sanitize_email( $_POST['email'] ?? '' );
    $phone   = sanitize_text_field( $_POST['phone'] ?? '' );
    $message = sanitize_textarea_field( $_POST['message'] ?? '' );

    // Basic validation
    if ( empty( $name ) || empty( $email ) || empty( $message ) ) {
        wp_send_json_error( 'Required fields are missing' );
    }

    if ( ! is_email( $email ) ) {
        wp_send_json_error( 'Invalid email' );
    }

    if ( strlen( $name ) < 3 ) {
        wp_send_json_error( 'Name must be at least 3 characters' );
    }

    if ( strlen( $message ) < 10 ) {
        wp_send_json_error( 'Message must be at least 10 characters' );
    }

    if ( ! empty( $phone ) && ! preg_match( '/^\d{10}$/', $phone ) ) {
        wp_send_json_error( 'Phone must be 10 digits' );
    }

    // Handle file upload with validation
    $file_url = '';
    if ( ! empty( $_FILES['file']['name'] ) ) {
        $file = $_FILES['file'];
        
        // Check if file is required
        if ( empty( $file['name'] ) ) {
            wp_send_json_error( 'File upload is required' );
        }

        // Validate file size
        $max_size = defined( 'CF_MAX_FILE_SIZE' ) ? CF_MAX_FILE_SIZE : ( 5 * 1024 * 1024 );
        if ( $file['size'] > $max_size ) {
            wp_send_json_error( 'File size exceeds maximum allowed size of 5 MB' );
        }

        // Validate file extension
        $allowed_extensions = defined( 'CF_ALLOWED_FILE_EXTENSIONS' ) ? CF_ALLOWED_FILE_EXTENSIONS : array( 'jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx' );
        $file_ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
        
        if ( ! in_array( $file_ext, $allowed_extensions, true ) ) {
            wp_send_json_error( 'File type is not allowed. Allowed types: ' . implode( ', ', array_map( 'strtoupper', $allowed_extensions ) ) );
        }

        $upload_overrides = array( 'test_form' => false );
        $movefile = wp_handle_upload( $file, $upload_overrides );

        if ( $movefile && ! isset( $movefile['error'] ) ) {
            $file_url = $movefile['url'];
        } else {
            wp_send_json_error( 'File upload failed: ' . ( isset( $movefile['error'] ) ? $movefile['error'] : 'Unknown error' ) );
        }
    } else {
        wp_send_json_error( 'File upload is required' );
    }

    // Save to database
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_form_entries';

    $result = $wpdb->insert(
        $table_name,
        array(
            'name'     => $name,
            'email'    => $email,
            'phone'    => $phone,
            'message'  => $message,
            'file_url' => $file_url,
        ),
        array( '%s', '%s', '%s', '%s', '%s' )
    );

    if ( $result === false ) {
        wp_send_json_error( 'Failed to save data' );
    }

    wp_send_json_success( 'Form submitted successfully' );
}

// Hook for logged-in and non-logged-in users
add_action( 'wp_ajax_cf_submit_form', 'cf_submit_form_ajax' );
add_action( 'wp_ajax_nopriv_cf_submit_form', 'cf_submit_form_ajax' );