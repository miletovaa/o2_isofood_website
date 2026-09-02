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
        'key' => 'group_facility',
        'title' => 'Facility / Equipment Details',
        'fields' => [
            [
                'key' => 'field_fa_summary',
                'label' => 'Short Summary',
                'name' => 'short_summary',
                'type' => 'textarea',
                'rows' => 3,
            ],
            [
                'key' => 'field_fa_manufacturer',
                'label' => 'Manufacturer / Model',
                'name' => 'manufacturer_model',
                'type' => 'text',
                'required' => 0,
            ],
            [
                'key' => 'field_fa_location',
                'label' => 'Location',
                'name' => 'location',
                'type' => 'text',
                'instructions' => 'e.g. "Lab 302, Reactor Centre Podgorica"',
                'required' => 0,
            ],
            [
                'key' => 'field_fa_research_areas',
                'label' => 'Research Area(s)',
                'name' => 'research_areas',
                'type' => 'taxonomy',
                'taxonomy' => 'research_topic',
                'field_type' => 'checkbox',
                'save_terms' => 1,
                'load_terms' => 1,
                'return_format' => 'id',
                'required' => 0,
            ],
        ],
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'facility']]],
    ]);
});
