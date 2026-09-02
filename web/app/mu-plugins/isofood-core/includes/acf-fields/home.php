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
        'key' => 'group_home_page',
        'title' => 'Home Page Content',
        'fields' => [
            [
                'key' => 'field_home_heading',
                'label' => 'Intro Heading',
                'name' => 'intro_heading',
                'type' => 'text',
            ],
            [
                'key' => 'field_home_intro_text',
                'label' => 'Intro Text',
                'name' => 'intro_text',
                'type' => 'wysiwyg',
                'tabs' => 'text',
                'media_upload' => 0,
            ],
            [
                'key' => 'field_home_group_photo',
                'label' => 'Group Photo',
                'name' => 'group_photo',
                'type' => 'image',
                'return_format' => 'id',
                'instructions' => 'Falls back to the Default Group Photo set in ISO-Food Settings if left empty.',
                'required' => 0,
            ],
        ],
        'location' => [[['param' => 'page_type', 'operator' => '==', 'value' => 'front_page']]],
    ]);
});
