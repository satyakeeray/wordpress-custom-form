<?php
/**
 * Configuration Constants for Custom Form Module
 *
 * @package CustomFormModule
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Google reCaptcha Settings
define( 'CF_RECAPTCHA_SITE_KEY', 'YOUR_RECAPTCHA_SITE_KEY' );
define( 'CF_RECAPTCHA_SECRET_KEY', 'YOUR_RECAPTCHA_SECRET_KEY' );

// Bypass reCaptcha for testing (set to false in production)
define( 'CF_BYPASS_RECAPTCHA', true );

// File Upload Settings
define( 'CF_ALLOWED_FILE_EXTENSIONS', array( 'jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx' ) );
define( 'CF_MAX_FILE_SIZE', 5 * 1024 * 1024 ); // 5 MB in bytes
