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
        'key' => 'group_project',
        'title' => 'Project Details',
        'fields' => [
            [
                'key' => 'field_pr_acronym',
                'label' => 'Acronym',
                'name' => 'acronym',
                'type' => 'text',
            ],
            [
                'key' => 'field_pr_funder',
                'label' => 'Funder',
                'name' => 'funder',
                'type' => 'text',
            ],
            [
                'key' => 'field_pr_grant_number',
                'label' => 'Grant / Contract Number',
                'name' => 'grant_number',
                'type' => 'text',
            ],
            [
                'key' => 'field_pr_start_date',
                'label' => 'Start Date',
                'name' => 'start_date',
                'type' => 'date_picker',
                'display_format' => 'F Y',
                'return_format' => 'Y-m-d',
            ],
            [
                'key' => 'field_pr_end_date',
                'label' => 'End Date',
                'name' => 'end_date',
                'type' => 'date_picker',
                'display_format' => 'F Y',
                'return_format' => 'Y-m-d',
            ],
            [
                'key' => 'field_pr_status',
                'label' => 'Status',
                'name' => 'status',
                'type' => 'select',
                'choices' => ['current' => 'Current', 'completed' => 'Completed'],
                'default_value' => 'current',
            ],
            [
                'key' => 'field_pr_partners',
                'label' => 'Partners',
                'name' => 'partners',
                'type' => 'relationship',
                'post_type' => ['collaborator'],
                'filters' => ['search'],
            ],
            [
                'key' => 'field_pr_short_description',
                'label' => 'Short Description',
                'name' => 'short_description',
                'type' => 'textarea',
                'rows' => 3,
            ],
            [
                'key' => 'field_pr_website',
                'label' => 'Project Website',
                'name' => 'project_website',
                'type' => 'url',
            ],
            [
                'key' => 'field_pr_research_areas',
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
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'project']]],
    ]);
});
