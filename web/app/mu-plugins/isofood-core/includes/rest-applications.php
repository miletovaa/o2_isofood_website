<?php

/**
 * POST /wp-json/isofood/v1/applications
 * Public endpoint backing the "Apply" form. Creates a private `application` post.
 */

namespace Isofood\Core;

if (! defined('ABSPATH')) {
    exit;
}

add_action('rest_api_init', function () {
    register_rest_route('isofood/v1', '/applications', [
        'methods' => 'POST',
        'callback' => __NAMESPACE__ . '\\handle_application_submission',
        'permission_callback' => '__return_true',
    ]);
});

function handle_application_submission(\WP_REST_Request $request)
{
    if (! wp_verify_nonce((string) $request->get_header('X-WP-Nonce'), 'wp_rest')) {
        return new \WP_Error('isofood_bad_nonce', 'Invalid request.', ['status' => 403]);
    }

    $params = $request->get_body_params();

    if (! passes_spam_check($params)) {
        // Silent rejection: respond as if successful so bots learn nothing.
        return rest_ensure_response(['success' => true]);
    }

    if (! passes_rate_limit('application', 5)) {
        return new \WP_Error('isofood_rate_limited', 'Too many submissions. Please try again later.', ['status' => 429]);
    }

    $required = ['full_name', 'email', 'highest_degree', 'field_of_study', 'english_proficiency'];
    foreach ($required as $field) {
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

    $cv_check = validate_upload('cv_file', ['pdf', 'doc', 'docx'], 5 * MB_IN_BYTES);
    if (is_wp_error($cv_check)) {
        return new \WP_Error('isofood_cv_invalid', $cv_check->get_error_message(), ['status' => 400]);
    }

    $letter_check = validate_upload('motivation_letter_file', ['pdf', 'doc', 'docx'], 5 * MB_IN_BYTES);
    if (is_wp_error($letter_check)) {
        return new \WP_Error('isofood_letter_invalid', $letter_check->get_error_message(), ['status' => 400]);
    }

    $position_id = ! empty($params['position']) ? (int) $params['position'] : 0;
    $position_title = $position_id ? get_the_title($position_id) : 'General / Speculative Application';

    $post_id = wp_insert_post([
        'post_type' => 'application',
        'post_status' => 'private',
        'post_title' => sprintf(
            '%s — %s — %s',
            sanitize_text_field($params['full_name']),
            $position_title,
            gmdate('Y-m-d'),
        ),
    ], true);

    if (is_wp_error($post_id)) {
        return new \WP_Error('isofood_save_failed', 'Could not save application.', ['status' => 500]);
    }

    if ($position_id) {
        update_field('position', $position_id, $post_id);
    }

    update_field('desired_position_type', array_map('intval', (array) ($params['desired_position_type'] ?? [])), $post_id);
    update_field('full_name', sanitize_text_field($params['full_name']), $post_id);
    update_field('email', sanitize_email($params['email']), $post_id);
    update_field('phone', sanitize_text_field($params['phone'] ?? ''), $post_id);
    update_field('country', sanitize_text_field($params['country'] ?? ''), $post_id);
    update_field('highest_degree', sanitize_text_field($params['highest_degree']), $post_id);
    update_field('field_of_study', sanitize_text_field($params['field_of_study']), $post_id);
    update_field('area_of_interest', array_map('intval', (array) ($params['area_of_interest'] ?? [])), $post_id);
    update_field('lab_techniques', array_map('sanitize_text_field', (array) ($params['lab_techniques'] ?? [])), $post_id);
    update_field('lab_techniques_other', sanitize_text_field($params['lab_techniques_other'] ?? ''), $post_id);
    update_field('english_proficiency', sanitize_text_field($params['english_proficiency']), $post_id);
    update_field('slovene_proficiency', sanitize_text_field($params['slovene_proficiency'] ?? ''), $post_id);
    update_field('other_languages', sanitize_text_field($params['other_languages'] ?? ''), $post_id);
    update_field('availability_date', sanitize_text_field($params['availability_date'] ?? ''), $post_id);
    update_field('additional_notes', sanitize_textarea_field($params['additional_notes'] ?? ''), $post_id);
    update_field('heard_about_us', sanitize_text_field($params['heard_about_us'] ?? ''), $post_id);
    update_field('heard_about_us_other', sanitize_text_field($params['heard_about_us_other'] ?? ''), $post_id);
    update_field('gdpr_consent', true, $post_id);
    update_field('submitted_at', current_time('mysql'), $post_id);
    update_field('application_status', 'new', $post_id);

    $cv_id = handle_private_upload('cv_file', $post_id);
    if (! is_wp_error($cv_id)) {
        update_field('cv_file', $cv_id, $post_id);
    }

    $letter_id = handle_private_upload('motivation_letter_file', $post_id);
    if (! is_wp_error($letter_id)) {
        update_field('motivation_letter_file', $letter_id, $post_id);
    }

    if ($position_id) {
        $extra_answers = format_position_extra_answers($position_id, (array) ($params['extra'] ?? []));
        update_field('additional_answers_summary', $extra_answers, $post_id);
    }

    send_application_notification($post_id, $position_title);

    return rest_ensure_response(['success' => true]);
}

function send_application_notification(int $post_id, string $position_title): void
{
    $to = get_option_value('application_notification_email', 'info@ijs-isofood.si');
    $edit_link = admin_url("post.php?post={$post_id}&action=edit");
    $full_name = get_field('full_name', $post_id);
    $email = get_field('email', $post_id);
    $phone = get_field('phone', $post_id);

    $extraAnswers = get_field('additional_answers_summary', $post_id);

    $subject = "New Job Application: {$position_title} — {$full_name}";
    $body = "A new application was submitted.\n\n"
        . "Position: {$position_title}\n"
        . "Name: {$full_name}\n"
        . "Email: {$email}\n"
        . "Phone: {$phone}\n"
        . ($extraAnswers ? "\n{$extraAnswers}\n" : '')
        . "\nReview it here: {$edit_link}\n";

    wp_mail($to, $subject, $body);
}
