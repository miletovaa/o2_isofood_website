<?php

namespace Isofood\Core;

if (! defined('ABSPATH')) {
    exit;
}

add_action('acf/init', function () {
    if (! function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_contact_message_data',
        'title' => 'Message',
        'fields' => [
            [
                'key' => 'field_cm_name',
                'label' => 'Name',
                'name' => 'name',
                'type' => 'text',
            ],
            [
                'key' => 'field_cm_email',
                'label' => 'Email',
                'name' => 'email',
                'type' => 'email',
            ],
            [
                'key' => 'field_cm_subject',
                'label' => 'Subject',
                'name' => 'subject',
                'type' => 'text',
            ],
            [
                'key' => 'field_cm_message',
                'label' => 'Message',
                'name' => 'message',
                'type' => 'textarea',
                'rows' => 6,
            ],
            [
                'key' => 'field_cm_consent',
                'label' => 'GDPR Consent Given',
                'name' => 'gdpr_consent',
                'type' => 'true_false',
                'ui' => 1,
            ],
            [
                'key' => 'field_cm_submitted_at',
                'label' => 'Submitted At',
                'name' => 'submitted_at',
                'type' => 'text',
            ],
        ],
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'contact_message']]],
    ]);

    acf_add_local_field_group([
        'key' => 'group_contact_message_review',
        'title' => 'Staff Review',
        'fields' => [
            [
                'key' => 'field_cm_status',
                'label' => 'Status',
                'name' => 'message_status',
                'type' => 'select',
                'choices' => ['new' => 'New', 'read' => 'Read', 'replied' => 'Replied', 'archived' => 'Archived'],
                'default_value' => 'new',
            ],
        ],
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'contact_message']]],
        'position' => 'side',
    ]);
});
