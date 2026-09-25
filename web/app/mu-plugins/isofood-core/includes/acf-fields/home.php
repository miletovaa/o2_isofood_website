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
                'key' => 'field_home_hero_photo',
                'label' => 'Hero Photo',
                'name' => 'hero_photo',
                'type' => 'image',
                'return_format' => 'id',
                'instructions' => 'Shown at the top of the Home page, next to the intro text. Falls back to the Default Group Photo set in ISO-Food Settings if left empty.',
                'required' => 0,
            ],
            [
                'key' => 'field_home_highlights_photo',
                'label' => 'News Photo',
                'name' => 'highlights_photo',
                'type' => 'image',
                'return_format' => 'id',
                'instructions' => 'Shown next to the News section. Falls back to the Default Group Photo set in ISO-Food Settings if left empty.',
                'required' => 0,
            ],
            [
                'key' => 'field_home_publications_photo',
                'label' => 'Publications Photo',
                'name' => 'publications_photo',
                'type' => 'image',
                'return_format' => 'id',
                'instructions' => 'Shown next to the Publications section. Falls back to the Default Group Photo set in ISO-Food Settings if left empty.',
                'required' => 0,
            ],
            [
                'key' => 'field_home_positions_photo',
                'label' => 'Positions Photo',
                'name' => 'positions_photo',
                'type' => 'image',
                'return_format' => 'id',
                'instructions' => 'Shown in the "Our Positions" teaser near the bottom of the Home page. Falls back to the Default Group Photo set in ISO-Food Settings if left empty.',
                'required' => 0,
            ],
        ],
        'location' => [[['param' => 'page_type', 'operator' => '==', 'value' => 'front_page']]],
    ]);
});
