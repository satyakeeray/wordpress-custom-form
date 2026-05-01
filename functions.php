<?php
// Include the custom form module
require_once get_template_directory() . '/custom-form-module/custom-form-module.php';

// Enqueue theme styles
function custom_form_theme_enqueue_styles() {
    wp_enqueue_style( 'custom-form-theme-style', get_stylesheet_uri(), array(), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'custom_form_theme_enqueue_styles' );