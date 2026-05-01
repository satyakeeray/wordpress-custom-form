<?php
/**
 * Custom Form Module - Main Loader
 *
 * @package CustomFormModule
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Include configuration
require_once __DIR__ . '/config.php';

// Include all module files
require_once __DIR__ . '/database/create-table.php';
require_once __DIR__ . '/frontend/form-shortcode.php';
require_once __DIR__ . '/frontend/ajax-handler.php';
require_once __DIR__ . '/admin/admin-menu.php';
require_once __DIR__ . '/admin/admin-page.php';
require_once __DIR__ . '/admin/ajax-admin.php';
require_once __DIR__ . '/exports/export-csv.php';

/**
 * Enqueue frontend scripts and styles
 */
function cf_enqueue_frontend_scripts() {
    // Enqueue jQuery (WordPress includes it by default)
    wp_enqueue_script( 'jquery' );

    // Enqueue jQuery Validate
    wp_enqueue_script( 'jquery-validate', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js', array( 'jquery' ), '1.19.5', true );

    // Enqueue SweetAlert
    wp_enqueue_script( 'sweetalert', 'https://cdn.jsdelivr.net/npm/sweetalert2@11', array(), '11.0.0', true );

    // Enqueue Google reCaptcha (only if not bypassed)
    if ( ( ! defined( 'CF_BYPASS_RECAPTCHA' ) || ! CF_BYPASS_RECAPTCHA ) && defined( 'CF_RECAPTCHA_SITE_KEY' ) ) {
        wp_enqueue_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js', array(), null, true );
    }

    // Enqueue custom form.js
    wp_enqueue_script( 'cf-form-js', get_template_directory_uri() . '/custom-form-module/frontend/form.js', array( 'jquery', 'jquery-validate', 'sweetalert' ), '1.0.0', true );

    // Localize AJAX
    wp_localize_script( 'cf-form-js', 'cf_ajax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'cf_submit_form_nonce' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'cf_enqueue_frontend_scripts' );

/**
 * Enqueue admin scripts and styles only on our admin page
 */
function cf_enqueue_admin_scripts( $hook ) {
    // Only load on our admin page
    if ( 'toplevel_page_custom-forms' !== $hook ) {
        return;
    }

    // Enqueue Font Awesome
    wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0' );

    // Enqueue DataTables CSS
    wp_enqueue_style( 'datatables-css', 'https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css', array(), '1.13.4' );

    // Enqueue DataTables JS
    wp_enqueue_script( 'datatables-js', 'https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js', array( 'jquery' ), '1.13.4', true );

    // Enqueue SweetAlert
    wp_enqueue_script( 'sweetalert', 'https://cdn.jsdelivr.net/npm/sweetalert2@11', array(), '11.0.0', true );

    // Enqueue custom admin.js
    wp_enqueue_script( 'cf-admin-js', get_template_directory_uri() . '/custom-form-module/admin/admin.js', array( 'jquery', 'datatables-js', 'sweetalert' ), '1.0.0', true );

    // Localize admin AJAX
    wp_localize_script( 'cf-admin-js', 'cf_admin_ajax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'cf_admin_nonce' ),
    ) );
}
add_action( 'admin_enqueue_scripts', 'cf_enqueue_admin_scripts' );