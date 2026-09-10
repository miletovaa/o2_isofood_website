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
        'key' => 'group_position',
        'title' => 'Position Details',
        'fields' => [
            [
                'key' => 'field_pos_field_of_work',
                'label' => 'Field of Work',
                'name' => 'field_of_work',
                'type' => 'text',
                'instructions' => 'e.g. "Food Chemistry / Chemistry / Environment / Health"',
            ],
            [
                'key' => 'field_pos_responsibilities',
                'label' => 'Job Description & Responsibilities',
                'name' => 'responsibilities',
                'type' => 'textarea',
                'instructions' => 'One responsibility per line — each becomes a bullet point.',
                'rows' => 6,
            ],
            [
                'key' => 'field_pos_requirements',
                'label' => 'Required Education & Qualifications',
                'name' => 'requirements',
                'type' => 'textarea',
                'instructions' => 'One requirement per line — each becomes a bullet point.',
                'rows' => 6,
            ],
            [
                'key' => 'field_pos_additional_requirements',
                'label' => 'Additional Requirements',
                'name' => 'additional_requirements',
                'type' => 'textarea',
                'instructions' => 'One item per line — each becomes a bullet point.',
                'rows' => 4,
                'required' => 0,
            ],
            [
                'key' => 'field_pos_expected_competencies',
                'label' => 'Expected Knowledge & Competencies',
                'name' => 'expected_competencies',
                'type' => 'textarea',
                'instructions' => 'One item per line — each becomes a bullet point.',
                'rows' => 4,
                'required' => 0,
            ],
            [
                'key' => 'field_pos_offer',
                'label' => 'We Offer',
                'name' => 'offer',
                'type' => 'textarea',
                'instructions' => 'One item per line — each becomes a bullet point.',
                'rows' => 6,
            ],
            [
                'key' => 'field_pos_location',
                'label' => 'Workplace Location',
                'name' => 'workplace_location',
                'type' => 'text',
            ],
            [
                'key' => 'field_pos_deadline',
                'label' => 'Application Deadline',
                'name' => 'application_deadline',
                'type' => 'date_picker',
                'display_format' => 'j F Y',
                'return_format' => 'Y-m-d',
            ],
            [
                'key' => 'field_pos_start_date',
                'label' => 'Expected Start Date',
                'name' => 'start_date',
                'type' => 'date_picker',
                'display_format' => 'j F Y',
                'return_format' => 'Y-m-d',
                'required' => 0,
            ],
            [
                'key' => 'field_pos_flexible_start',
                'label' => 'Flexible Start Date',
                'name' => 'flexible_start_date',
                'type' => 'true_false',
                'ui' => 1,
                'instructions' => 'Shows "As soon as possible" instead of a fixed date.',
            ],
            [
                'key' => 'field_pos_status',
                'label' => 'Status',
                'name' => 'status',
                'type' => 'select',
                'choices' => ['open' => 'Open', 'closed' => 'Closed', 'filled' => 'Filled'],
                'default_value' => 'open',
            ],
        ],
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'position']]],
    ]);
});
