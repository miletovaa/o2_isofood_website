<?php

/**
 * Polylang integration: mark our CPTs/taxonomies as translatable and register
 * hardcoded UI strings for the Strings Translation screen, so Blade templates
 * never need per-language duplication.
 */

namespace Isofood\Core;

if (! defined('ABSPATH')) {
    exit;
}

add_filter('pll_get_post_types', function (array $post_types): array {
    $translatable = [
        'team_member', 'research_area', 'project', 'publication',
        'facility', 'collaborator', 'position',
    ];

    foreach ($translatable as $post_type) {
        $post_types[$post_type] = $post_type;
    }

    // application & contact_message are intentionally excluded: private submissions, not content.
    return $post_types;
});

add_filter('pll_get_taxonomies', function (array $taxonomies): array {
    foreach (['research_topic', 'position_type', 'collaborator_type', 'facility_type'] as $tax) {
        $taxonomies[$tax] = $tax;
    }

    return $taxonomies;
});

add_action('init', __NAMESPACE__ . '\\register_polylang_strings', 20);

function register_polylang_strings(): void
{
    if (! function_exists('pll_register_string')) {
        return;
    }

    $strings = [
        'Apply' => 'Apply',
        'Apply for this position' => 'Apply for this position',
        'Applying for' => 'Applying for',
        'General / Speculative Application' => 'General / Speculative Application',
        'Submit Application' => 'Submit Application',
        'Send Message' => 'Send Message',
        'Read more' => 'Read more',
        'Our Team' => 'Our Team',
        'Research Areas' => 'Research Areas',
        'Research Projects' => 'Research Projects',
        'Publications' => 'Publications',
        'Methods & Facilities' => 'Methods & Facilities',
        'Collaborators' => 'Collaborators',
        'Open Positions' => 'Open Positions',
        'Current' => 'Current',
        'Completed' => 'Completed',
        'Contact Us' => 'Contact Us',
        'No positions are currently open. Speculative applications are still welcome.'
            => 'No positions are currently open. Speculative applications are still welcome.',
    ];

    foreach ($strings as $name => $string) {
        pll_register_string($name, $string, 'ISO-Food Theme');
    }
}

/**
 * Wrapper: falls back to the raw string if Polylang isn't active (local dev safety).
 */
function translate_string(string $string): string
{
    return function_exists('pll__') ? pll__($string) : $string;
}
