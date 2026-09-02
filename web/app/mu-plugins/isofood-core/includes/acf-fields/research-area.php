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
        'key' => 'group_research_area',
        'title' => 'Research Area Details',
        'fields' => [
            [
                'key' => 'field_ra_summary',
                'label' => 'Short Summary',
                'name' => 'short_summary',
                'type' => 'textarea',
                'instructions' => 'Shown on archive cards and Home highlights.',
                'rows' => 3,
            ],
            [
                'key' => 'field_ra_methods',
                'label' => 'Methods Used',
                'name' => 'methods_used',
                'type' => 'textarea',
                'instructions' => 'One method per line.',
                'rows' => 4,
            ],
            [
                'key' => 'field_ra_key_references',
                'label' => 'Key References',
                'name' => 'key_references',
                'type' => 'wysiwyg',
                'tabs' => 'text',
                'media_upload' => 0,
            ],
            [
                'key' => 'field_ra_featured',
                'label' => 'Featured on Home',
                'name' => 'featured_on_home',
                'type' => 'true_false',
                'ui' => 1,
            ],
        ],
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'research_area']]],
        'instructions_placement' => 'field',
    ]);

    acf_add_local_field_group([
        'key' => 'group_research_area_content_note',
        'title' => 'Full Description & Figures',
        'fields' => [
            [
                'key' => 'field_ra_note',
                'label' => '',
                'name' => '',
                'type' => 'message',
                'message' => 'Use the main content editor above for the full description. Add representative images/figures with the native Gutenberg "Gallery" block.',
            ],
        ],
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'research_area']]],
        'position' => 'side',
        'style' => 'seamless',
    ]);
});
