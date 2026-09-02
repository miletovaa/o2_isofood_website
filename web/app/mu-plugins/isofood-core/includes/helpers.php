<?php

/**
 * Shared helpers: site-wide options, spam mitigation, notification emails.
 */

namespace Isofood\Core;

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Read a value from the isofood_options option array (see includes/settings-page.php).
 */
function get_option_value(string $key, $default = '')
{
    $options = \get_option('isofood_options', []);

    return isset($options[$key]) && $options[$key] !== '' ? $options[$key] : $default;
}

/**
 * Basic honeypot + time-trap spam check for public form submissions.
 *
 * @param array $params  The request body params (already sanitized keys expected: isofood_hp, isofood_ts).
 */
function passes_spam_check(array $params): bool
{
    // Honeypot: a field real users never see or fill in via CSS-hidden input.
    if (! empty($params['isofood_hp'])) {
        return false;
    }

    // Time-trap: reject submissions completed suspiciously fast (< 3s after page render).
    $rendered_at = isset($params['isofood_ts']) ? (int) $params['isofood_ts'] : 0;
    if ($rendered_at <= 0 || (time() - $rendered_at) < 3) {
        return false;
    }

    return true;
}

/**
 * Per-IP rate limit for a named form action. Returns true if the request is allowed.
 */
function passes_rate_limit(string $action, int $max_per_hour = 5): bool
{
    $ip = isofood_client_ip();
    $key = 'isofood_rl_' . $action . '_' . md5($ip);
    $count = (int) get_transient($key);

    if ($count >= $max_per_hour) {
        return false;
    }

    set_transient($key, $count + 1, HOUR_IN_SECONDS);

    return true;
}

function isofood_client_ip(): string
{
    foreach (['HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $header) {
        if (! empty($_SERVER[$header])) {
            $ip = explode(',', $_SERVER[$header])[0];

            return trim($ip);
        }
    }

    return '0.0.0.0';
}

/**
 * Handle a single uploaded file for a private submission (Application/Contact attachments).
 * Randomizes the filename and attaches it as a private, non-public media item.
 *
 * @return int|\WP_Error Attachment ID on success.
 */
function handle_private_upload(string $field_name, int $parent_post_id)
{
    if (empty($_FILES[$field_name]) || empty($_FILES[$field_name]['name'])) {
        return new \WP_Error('isofood_no_file', __('No file was uploaded.', 'isofood'));
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    add_filter('sanitize_file_name', __NAMESPACE__ . '\\randomize_upload_filename', 10, 1);
    $attachment_id = media_handle_upload($field_name, $parent_post_id);
    remove_filter('sanitize_file_name', __NAMESPACE__ . '\\randomize_upload_filename', 10);

    if (is_wp_error($attachment_id)) {
        return $attachment_id;
    }

    // Keep applicant documents out of the public Media Library / REST media endpoint.
    wp_update_post([
        'ID' => $attachment_id,
        'post_status' => 'private',
    ]);

    return $attachment_id;
}

function randomize_upload_filename(string $filename): string
{
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $random = wp_generate_password(20, false, false);

    return $random . ($ext ? ".{$ext}" : '');
}

/**
 * Validate an uploaded file's extension/size before handing it to WordPress.
 */
function validate_upload(string $field_name, array $allowed_ext, int $max_bytes)
{
    if (empty($_FILES[$field_name]['name'])) {
        return new \WP_Error('isofood_no_file', __('This file is required.', 'isofood'));
    }

    $file = $_FILES[$field_name];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return new \WP_Error('isofood_upload_error', __('There was a problem uploading the file.', 'isofood'));
    }

    if ($file['size'] > $max_bytes) {
        return new \WP_Error('isofood_file_too_large', __('The file is too large.', 'isofood'));
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (! in_array($ext, $allowed_ext, true)) {
        return new \WP_Error('isofood_invalid_file_type', __('Unsupported file type.', 'isofood'));
    }

    return true;
}
