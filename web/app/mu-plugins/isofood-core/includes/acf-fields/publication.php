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
        'key' => 'group_publication',
        'title' => 'Publication Details',
        'fields' => [
            [
                'key' => 'field_pub_authors',
                'label' => 'ISO-Food Authors',
                'name' => 'isofood_authors',
                'type' => 'relationship',
                'post_type' => ['team_member'],
                'filters' => ['search'],
            ],
            [
                'key' => 'field_pub_external_authors',
                'label' => 'Additional / External Authors',
                'name' => 'external_authors',
                'type' => 'text',
                'instructions' => 'Comma-separated, for co-authors outside the group.',
            ],
            [
                'key' => 'field_pub_type',
                'label' => 'Publication Type',
                'name' => 'publication_type',
                'type' => 'select',
                'choices' => [
                    'journal_article' => 'Journal Article',
                    'conference_paper' => 'Conference Paper',
                    'book_chapter' => 'Book Chapter',
                    'report' => 'Report',
                    'thesis' => 'Thesis',
                    'other' => 'Other',
                ],
            ],
            [
                'key' => 'field_pub_venue',
                'label' => 'Journal / Venue',
                'name' => 'venue',
                'type' => 'text',
            ],
            [
                'key' => 'field_pub_year',
                'label' => 'Year',
                'name' => 'year',
                'type' => 'number',
            ],
            [
                'key' => 'field_pub_doi',
                'label' => 'DOI / Link',
                'name' => 'doi_link',
                'type' => 'url',
            ],
            [
                'key' => 'field_pub_abstract',
                'label' => 'Abstract',
                'name' => 'abstract',
                'type' => 'textarea',
                'rows' => 4,
                'required' => 0,
            ],
            [
                'key' => 'field_pub_featured',
                'label' => 'Featured / Highlight',
                'name' => 'featured',
                'type' => 'true_false',
                'ui' => 1,
                'instructions' => 'Surfaces this publication on the Home page highlights.',
            ],
            [
                'key' => 'field_pub_research_areas',
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
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'publication']]],
    ]);
});
