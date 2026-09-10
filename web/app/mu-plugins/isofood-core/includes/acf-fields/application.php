<?php

/**
 * Field groups for the `application` CPT. These are written to directly by the
 * REST handler (includes/rest-applications.php) as post meta matching these keys —
 * defined here purely so staff get an organized wp-admin review screen.
 */

namespace Isofood\Core;

if (! defined('ABSPATH')) {
    exit;
}

add_action('acf/init', function () {
    if (! function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_application_data',
        'title' => 'Applicant Submission',
        'fields' => [
            [
                'key' => 'field_app_position',
                'label' => 'Position Applying For',
                'name' => 'position',
                'type' => 'post_object',
                'post_type' => ['position'],
                'instructions' => 'Empty = general / speculative application.',
                'required' => 0,
            ],
            [
                'key' => 'field_app_desired_type',
                'label' => 'Desired Position Type',
                'name' => 'desired_position_type',
                'type' => 'taxonomy',
                'taxonomy' => 'position_type',
                'field_type' => 'checkbox',
                'return_format' => 'id',
                'required' => 0,
            ],
            [
                'key' => 'field_app_full_name',
                'label' => 'Full Name',
                'name' => 'full_name',
                'type' => 'text',
            ],
            [
                'key' => 'field_app_email',
                'label' => 'Email',
                'name' => 'email',
                'type' => 'email',
            ],
            [
                'key' => 'field_app_phone',
                'label' => 'Phone',
                'name' => 'phone',
                'type' => 'text',
                'required' => 0,
            ],
            [
                'key' => 'field_app_country',
                'label' => 'Nationality / Country',
                'name' => 'country',
                'type' => 'text',
                'required' => 0,
            ],
            [
                'key' => 'field_app_degree',
                'label' => 'Highest Degree Level',
                'name' => 'highest_degree',
                'type' => 'radio',
                'choices' => [
                    'bsc' => 'BSc',
                    'msc' => 'MSc',
                    'phd' => 'PhD',
                    'postdoc_other' => 'Postdoc / Other',
                ],
            ],
            [
                'key' => 'field_app_field_of_study',
                'label' => 'Field of Study',
                'name' => 'field_of_study',
                'type' => 'text',
            ],
            [
                'key' => 'field_app_area_of_interest',
                'label' => 'Area of Interest',
                'name' => 'area_of_interest',
                'type' => 'taxonomy',
                'taxonomy' => 'research_topic',
                'field_type' => 'checkbox',
                'return_format' => 'id',
                'required' => 0,
            ],
            [
                'key' => 'field_app_lab_techniques',
                'label' => 'Lab Technique Familiarity',
                'name' => 'lab_techniques',
                'type' => 'checkbox',
                'choices' => [
                    'gc_ms' => 'GC-MS',
                    'lc_ms' => 'LC-MS',
                    'irms' => 'IRMS',
                    'nmr' => 'NMR',
                    'icp_ms' => 'ICP-MS',
                    'r' => 'R',
                    'python' => 'Python',
                    'other' => 'Other',
                ],
                'required' => 0,
            ],
            [
                'key' => 'field_app_lab_techniques_other',
                'label' => 'Other Lab Technique (specify)',
                'name' => 'lab_techniques_other',
                'type' => 'text',
                'required' => 0,
                'conditional_logic' => [[['field' => 'field_app_lab_techniques', 'operator' => '==', 'value' => 'other']]],
            ],
            [
                'key' => 'field_app_english',
                'label' => 'English Proficiency',
                'name' => 'english_proficiency',
                'type' => 'select',
                'choices' => ['basic' => 'Basic', 'intermediate' => 'Intermediate', 'advanced' => 'Advanced', 'native' => 'Native'],
            ],
            [
                'key' => 'field_app_slovene',
                'label' => 'Slovene Proficiency',
                'name' => 'slovene_proficiency',
                'type' => 'select',
                'choices' => ['none' => 'None', 'basic' => 'Basic', 'intermediate' => 'Intermediate', 'advanced' => 'Advanced', 'native' => 'Native'],
                'required' => 0,
            ],
            [
                'key' => 'field_app_other_languages',
                'label' => 'Other Languages',
                'name' => 'other_languages',
                'type' => 'text',
                'required' => 0,
            ],
            [
                'key' => 'field_app_availability',
                'label' => 'Availability / Earliest Start Date',
                'name' => 'availability_date',
                'type' => 'date_picker',
                'display_format' => 'j F Y',
                'return_format' => 'Y-m-d',
                'required' => 0,
            ],
            [
                'key' => 'field_app_cv',
                'label' => 'CV',
                'name' => 'cv_file',
                'type' => 'file',
                'return_format' => 'id',
            ],
            [
                'key' => 'field_app_motivation_file',
                'label' => 'Motivation Letter',
                'name' => 'motivation_letter_file',
                'type' => 'file',
                'return_format' => 'id',
            ],
            [
                'key' => 'field_app_notes',
                'label' => 'Additional Notes (from applicant)',
                'name' => 'additional_notes',
                'type' => 'textarea',
                'rows' => 4,
                'required' => 0,
            ],
            [
                'key' => 'field_app_heard_about',
                'label' => 'How did you hear about us?',
                'name' => 'heard_about_us',
                'type' => 'select',
                'choices' => [
                    'university' => 'University / Faculty',
                    'linkedin' => 'LinkedIn',
                    'website' => 'ISO-Food Website',
                    'conference' => 'Conference / Event',
                    'referral' => 'Referral',
                    'other' => 'Other',
                ],
                'required' => 0,
            ],
            [
                'key' => 'field_app_heard_about_other',
                'label' => 'Other (specify)',
                'name' => 'heard_about_us_other',
                'type' => 'text',
                'required' => 0,
                'conditional_logic' => [[['field' => 'field_app_heard_about', 'operator' => '==', 'value' => 'other']]],
            ],
            [
                'key' => 'field_app_consent',
                'label' => 'GDPR Consent Given',
                'name' => 'gdpr_consent',
                'type' => 'true_false',
                'ui' => 1,
            ],
            [
                'key' => 'field_app_submitted_at',
                'label' => 'Submitted At',
                'name' => 'submitted_at',
                'type' => 'text',
                'instructions' => 'Set automatically on submission.',
            ],
            [
                'key' => 'field_app_extra_answers',
                'label' => 'Position-Specific Answers',
                'name' => 'additional_answers_summary',
                'type' => 'textarea',
                'instructions' => 'Answers to this position\'s custom application questions, set automatically on submission.',
                'rows' => 5,
                'required' => 0,
            ],
        ],
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'application']]],
    ]);

    acf_add_local_field_group([
        'key' => 'group_application_review',
        'title' => 'Staff Review',
        'fields' => [
            [
                'key' => 'field_app_status',
                'label' => 'Status',
                'name' => 'application_status',
                'type' => 'select',
                'choices' => [
                    'new' => 'New',
                    'reviewed' => 'Reviewed',
                    'shortlisted' => 'Shortlisted',
                    'interview' => 'Interview',
                    'rejected' => 'Rejected',
                    'hired' => 'Hired',
                ],
                'default_value' => 'new',
            ],
            [
                'key' => 'field_app_staff_notes',
                'label' => 'Staff Notes (private)',
                'name' => 'staff_notes',
                'type' => 'textarea',
                'rows' => 4,
                'required' => 0,
            ],
        ],
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'application']]],
        'position' => 'side',
    ]);
});
