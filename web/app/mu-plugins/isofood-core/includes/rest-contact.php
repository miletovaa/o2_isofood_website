<?php

/**
 * POST /wp-json/isofood/v1/contact
 * Public endpoint backing the Contact page form. Creates a private `contact_message` post.
 */

namespace Isofood\Core;

if (! defined('ABSPATH')) {
    exit;
}

add_action('rest_api_init', function () {
    register_rest_route('isofood/v1', '/contact', [
        'methods' => 'POST',
        'callback' => __NAMESPACE__ . '\\handle_contact_submission',
        'permission_callback' => '__return_true',
    ]);
});

function handle_contact_submission(\WP_REST_Request $request)
{
    if (! wp_verify_nonce((string) $request->get_header('X-WP-Nonce'), 'wp_rest')) {
        return new \WP_Error('isofood_bad_nonce', 'Invalid request.', ['status' => 403]);
    }

    $params = $request->get_body_params();

    if (! passes_spam_check($params)) {
        return rest_ensure_response(['success' => true]);
    }

    if (! passes_rate_limit('contact', 5)) {
        return new \WP_Error('isofood_rate_limited', 'Too many submissions. Please try again later.', ['status' => 429]);
    }

    foreach (['name', 'email', 'message'] as $field) {
        if (empty($params[$field])) {
            return new \WP_Error('isofood_missing_field', "The field \"{$field}\" is required.", ['status' => 400]);
        }
    }

    if (empty($params['gdpr_consent'])) {
        return new \WP_Error('isofood_no_consent', 'GDPR consent is required.', ['status' => 400]);
    }

    if (! is_email($params['email'])) {
        return new \WP_Error('isofood_invalid_email', 'Please provide a valid email address.', ['status' => 400]);
    }

    $subject = ! empty($params['subject']) ? sanitize_text_field($params['subject']) : 'General Inquiry';

    $post_id = wp_insert_post([
        'post_type' => 'contact_message',
        'post_status' => 'private',
        'post_title' => sprintf('%s — %s', sanitize_text_field($params['name']), gmdate('Y-m-d H:i')),
    ], true);

    if (is_wp_error($post_id)) {
        return new \WP_Error('isofood_save_failed', 'Could not save message.', ['status' => 500]);
    }

    update_field('name', sanitize_text_field($params['name']), $post_id);
    update_field('email', sanitize_email($params['email']), $post_id);
    update_field('subject', $subject, $post_id);
    update_field('message', sanitize_textarea_field($params['message']), $post_id);
    update_field('gdpr_consent', true, $post_id);
    update_field('submitted_at', current_time('mysql'), $post_id);
    update_field('message_status', 'new', $post_id);

    send_contact_notification($post_id, $subject);

    return rest_ensure_response(['success' => true]);
}

function send_contact_notification(int $post_id, string $subject): void
{
    $to = get_option_value('contact_notification_email', 'info@ijs-isofood.si');
    $edit_link = admin_url("post.php?post={$post_id}&action=edit");
    $name = get_field('name', $post_id);
    $email = get_field('email', $post_id);
    $message = get_field('message', $post_id);

    $mail_subject = "New Contact Message: {$subject}";
    $body = "A new contact message was submitted.\n\n"
        . "Name: {$name}\n"
        . "Email: {$email}\n"
        . "Subject: {$subject}\n\n"
        . "Message:\n{$message}\n\n"
        . "Review it here: {$edit_link}\n";

    wp_mail($to, $mail_subject, $body);
}
