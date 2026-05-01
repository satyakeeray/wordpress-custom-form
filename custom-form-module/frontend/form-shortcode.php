<?php
/**
 * Frontend Shortcode for Custom Form
 *
 * @package CustomFormModule
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Shortcode callback to render the form
 */
function cf_form_shortcode() {
    ob_start();
    ?>
    <form id="cf-form" method="post" enctype="multipart/form-data">
        <div>
            <label for="cf-name">Name *</label>
            <input type="text" id="cf-name" name="name" required>
        </div>
        <div>
            <label for="cf-email">Email *</label>
            <input type="email" id="cf-email" name="email" required>
        </div>
        <div>
            <label for="cf-phone">Phone</label>
            <input type="tel" id="cf-phone" name="phone">
        </div>
        <div>
            <label for="cf-file">File Upload *</label>
            <input type="file" id="cf-file" name="file" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" required>
            <small class="cf-file-info">Allowed: JPG, JPEG, PNG, PDF, DOC, DOCX (Max 5 MB)</small>
        </div>
        <div>
            <label for="cf-message">Message *</label>
            <textarea id="cf-message" name="message" required></textarea>
        </div>
        <?php if ( ! defined( 'CF_BYPASS_RECAPTCHA' ) || ! CF_BYPASS_RECAPTCHA ) : ?>
        <div class="g-recaptcha" data-sitekey="<?php echo esc_attr( CF_RECAPTCHA_SITE_KEY ); ?>"></div>
        <?php endif; ?>
        <div id="cf-error-message" class="cf-error-message"></div>
        <button type="submit">Submit</button>
        <input type="hidden" name="action" value="cf_submit_form">
        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce( 'cf_submit_form_nonce' ); ?>">
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode( 'custom_form', 'cf_form_shortcode' );