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
        'key' => 'group_team_member',
        'title' => 'Team Member Details',
        'fields' => [
            [
                'key' => 'field_tm_photo',
                'label' => 'Photo',
                'name' => 'photo',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'medium',
            ],
            [
                'key' => 'field_tm_academic_title',
                'label' => 'Academic Title',
                'name' => 'academic_title',
                'type' => 'text',
                'instructions' => 'e.g. "PhD", "Assoc. Prof.", "MSc"',
            ],
            [
                'key' => 'field_tm_position',
                'label' => 'Position',
                'name' => 'position',
                'type' => 'select',
                'choices' => [
                    'principal_investigator' => 'Principal Investigator',
                    'researcher' => 'Researcher',
                    'research_associate' => 'Research Associate',
                    'young_researcher' => 'Young Researcher',
                    'postdoc' => 'Postdoctoral Researcher',
                    'assistant' => 'Assistant',
                    'phd_researcher' => 'PhD Researcher',
                    'msc_student' => 'MSc Student',
                    'technician' => 'Technician',
                    'professional_research_associate' => 'Professional Research Associate',
                    'administrative_staff' => 'Administrative Staff',
                ],
            ],
            [
                'key' => 'field_tm_email',
                'label' => 'Email',
                'name' => 'email',
                'type' => 'email',
            ],
            [
                'key' => 'field_tm_orcid',
                'label' => 'ORCID',
                'name' => 'orcid',
                'type' => 'text',
                'instructions' => 'e.g. 0000-0002-1825-0097',
            ],
            [
                'key' => 'field_tm_sicris',
                'label' => 'SICRIS / ARIS Researcher Number',
                'name' => 'sicris_number',
                'type' => 'text',
                'required' => 0,
            ],
            [
                'key' => 'field_tm_bio',
                'label' => 'Short Biography',
                'name' => 'short_biography',
                'type' => 'textarea',
                'instructions' => 'Aim for 60–100 words.',
                'rows' => 5,
            ],
            [
                'key' => 'field_tm_research_interests',
                'label' => 'Research Interests',
                'name' => 'research_interests',
                'type' => 'taxonomy',
                'taxonomy' => 'research_topic',
                'field_type' => 'checkbox',
                'instructions' => 'Select 3–6 keywords/areas.',
                'add_term' => 1,
                'save_terms' => 1,
                'load_terms' => 1,
                'return_format' => 'id',
            ],
            [
                'key' => 'field_tm_publications_link',
                'label' => 'Publications / Profile Link',
                'name' => 'profile_link',
                'type' => 'url',
                'instructions' => 'e.g. link to a personal Google Scholar / SICRIS profile.',
            ],
        ],
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'team_member']]],
    ]);
});
