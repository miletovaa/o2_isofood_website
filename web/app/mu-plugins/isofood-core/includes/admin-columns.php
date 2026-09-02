<?php

/**
 * Custom wp-admin list-table columns and filters for Applications & Contact Messages.
 */

namespace Isofood\Core;

if (! defined('ABSPATH')) {
    exit;
}

/* ---------------------------------------------------------------------- *
 * Applications
 * ---------------------------------------------------------------------- */

add_filter('manage_application_posts_columns', function (array $columns): array {
    $columns = array_slice($columns, 0, 1, true) + [
        'position' => 'Position',
        'applicant_email' => 'Email',
        'app_status' => 'Status',
        'documents' => 'Documents',
    ] + array_slice($columns, 1, null, true);

    unset($columns['date']);
    $columns['submitted'] = 'Submitted';

    return $columns;
});

add_action('manage_application_posts_custom_column', function (string $column, int $post_id): void {
    switch ($column) {
        case 'position':
            $position = get_field('position', $post_id);
            echo $position ? esc_html(get_the_title($position)) : 'General / Speculative';
            break;
        case 'applicant_email':
            echo esc_html((string) get_field('email', $post_id));
            break;
        case 'app_status':
            $status = get_field('application_status', $post_id) ?: 'new';
            printf('<span class="isofood-status isofood-status-%s">%s</span>', esc_attr($status), esc_html(ucfirst($status)));
            break;
        case 'documents':
            $cv = get_field('cv_file', $post_id);
            $letter = get_field('motivation_letter_file', $post_id);
            if ($cv) {
                printf('<a href="%s" target="_blank" rel="noopener">CV</a> ', esc_url(wp_get_attachment_url($cv)));
            }
            if ($letter) {
                printf('<a href="%s" target="_blank" rel="noopener">Letter</a>', esc_url(wp_get_attachment_url($letter)));
            }
            break;
        case 'submitted':
            echo esc_html((string) get_field('submitted_at', $post_id));
            break;
    }
}, 10, 2);

add_action('restrict_manage_posts', function (): void {
    global $typenow;

    if ($typenow !== 'application') {
        return;
    }

    $current_status = $_GET['app_status_filter'] ?? '';
    echo '<select name="app_status_filter"><option value="">All Statuses</option>';
    foreach (['new' => 'New', 'reviewed' => 'Reviewed', 'shortlisted' => 'Shortlisted', 'interview' => 'Interview', 'rejected' => 'Rejected', 'hired' => 'Hired'] as $value => $label) {
        printf('<option value="%s"%s>%s</option>', esc_attr($value), selected($current_status, $value, false), esc_html($label));
    }
    echo '</select>';

    $current_position = $_GET['position_filter'] ?? '';
    $positions = get_posts(['post_type' => 'position', 'numberposts' => -1]);
    echo '<select name="position_filter"><option value="">All Positions</option>';
    foreach ($positions as $position) {
        printf('<option value="%d"%s>%s</option>', $position->ID, selected($current_position, (string) $position->ID, false), esc_html($position->post_title));
    }
    echo '</select>';
});

add_action('pre_get_posts', function (\WP_Query $query): void {
    if (! is_admin() || ! $query->is_main_query()) {
        return;
    }

    if ($query->get('post_type') !== 'application') {
        return;
    }

    $meta_query = [];

    if (! empty($_GET['app_status_filter'])) {
        $meta_query[] = ['key' => 'application_status', 'value' => sanitize_text_field($_GET['app_status_filter'])];
    }

    if (! empty($_GET['position_filter'])) {
        $meta_query[] = ['key' => 'position', 'value' => (int) $_GET['position_filter']];
    }

    if ($meta_query) {
        $query->set('meta_query', $meta_query);
    }
});

/* ---------------------------------------------------------------------- *
 * Contact Messages
 * ---------------------------------------------------------------------- */

add_filter('manage_contact_message_posts_columns', function (array $columns): array {
    $columns = array_slice($columns, 0, 1, true) + [
        'sender_email' => 'Email',
        'subject' => 'Subject',
        'msg_status' => 'Status',
    ] + array_slice($columns, 1, null, true);

    unset($columns['date']);
    $columns['submitted'] = 'Submitted';

    return $columns;
});

add_action('manage_contact_message_posts_custom_column', function (string $column, int $post_id): void {
    switch ($column) {
        case 'sender_email':
            echo esc_html((string) get_field('email', $post_id));
            break;
        case 'subject':
            echo esc_html((string) get_field('subject', $post_id));
            break;
        case 'msg_status':
            $status = get_field('message_status', $post_id) ?: 'new';
            printf('<span class="isofood-status isofood-status-%s">%s</span>', esc_attr($status), esc_html(ucfirst($status)));
            break;
        case 'submitted':
            echo esc_html((string) get_field('submitted_at', $post_id));
            break;
    }
}, 10, 2);

add_action('admin_head', function (): void {
    echo '<style>
        .isofood-status { padding: 2px 8px; border-radius: 3px; background: #eee; font-size: 12px; }
        .isofood-status-new { background: #d4f4dd; }
        .isofood-status-rejected, .isofood-status-archived { background: #f4d4d4; }
        .isofood-status-hired, .isofood-status-replied { background: #d4e4f4; }
    </style>';
});
