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
        'key' => 'group_collaborator',
        'title' => 'Collaborator Details',
        'fields' => [
            [
                'key' => 'field_col_website',
                'label' => 'Website',
                'name' => 'website',
                'type' => 'url',
            ],
            [
                'key' => 'field_col_description',
                'label' => 'Description',
                'name' => 'short_description',
                'type' => 'wysiwyg',
                'instructions' => 'Supports paragraphs, bold text, and subheadings.',
                'tabs' => 'text',
                'media_upload' => 0,
                'required' => 0,
            ],
            [
                'key' => 'field_col_country',
                'label' => 'Country',
                'name' => 'country',
                'type' => 'text',
                'required' => 0,
            ],
        ],
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'collaborator']]],
    ]);
});
