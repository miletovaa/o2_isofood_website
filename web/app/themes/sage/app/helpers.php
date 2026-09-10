<?php

/**
 * Theme-level helper functions bridging to the isofood-core plugin.
 */

namespace App;

/**
 * Read a site-wide option set on the "ISO-Food Settings" admin page.
 */
function isofood_option(string $key, $default = '')
{
    if (! function_exists('\Isofood\Core\get_option_value')) {
        return $default;
    }

    return \Isofood\Core\get_option_value($key, $default);
}

/**
 * Polylang-aware string translation, falls back to the raw string when Polylang is inactive.
 */
function t(string $string): string
{
    return function_exists('pll__') ? pll__($string) : $string;
}

/**
 * Format an ACF Y-m-d date field for display.
 */
function format_date(?string $date, string $format = 'j F Y'): string
{
    if (! $date) {
        return '';
    }

    $timestamp = strtotime($date);

    return $timestamp ? date_i18n($format, $timestamp) : '';
}

/**
 * Resolve the human-readable label for an ACF select/radio field's saved value.
 */
function field_label(string $selector, $post_id = false): string
{
    $field = get_field_object($selector, $post_id);

    if (! $field || empty($field['value'])) {
        return '';
    }

    return $field['choices'][$field['value']] ?? (string) $field['value'];
}

/**
 * Split a plain "one item per line" textarea field into a clean array,
 * dropping blank lines. Used for admin-friendly bullet-list content.
 */
function lines_to_list(?string $text): array
{
    if (! $text) {
        return [];
    }

    return array_values(array_filter(array_map('trim', explode("\n", $text))));
}

/**
 * Posts of a given type tagged with a research_topic term (the taxonomy backing
 * the research_area CPT's cross-linking; see Isofood\Core\sync_research_area_term()).
 */
function related_by_research_area(int $term_id, string $post_type, int $count = 6): array
{
    if (! $term_id) {
        return [];
    }

    return get_posts([
        'post_type' => $post_type,
        'numberposts' => $count,
        'tax_query' => [[
            'taxonomy' => 'research_topic',
            'field' => 'term_id',
            'terms' => $term_id,
        ]],
    ]);
}

/**
 * Small inline-SVG icon set for research theme/area cards (Home's "Key Research
 * Themes" and the Research Areas archive). Keyed loosely so both a fixed set of
 * Home cards and arbitrary Research Area CPT posts (matched by slug) can share it.
 */
function research_theme_icon(string $key, string $class = 'h-6 w-6'): string
{
    $paths = [
        'authenticity' => '<path d="M12 2 3.5 6v6c0 5 3.6 8.6 8.5 10 4.9-1.4 8.5-5 8.5-10V6L12 2Z" /><path d="m8.5 12 2.5 2.5 5-5" />',
        'quality' => '<path d="M9 2v6.5L4.5 17a2 2 0 0 0 1.8 3h11.4a2 2 0 0 0 1.8-3L15 8.5V2" /><path d="M9 2h6" /><path d="M8 14h8" />',
        'environmental' => '<circle cx="12" cy="12" r="9" /><path d="M3 12h18" /><path d="M12 3a15 15 0 0 1 0 18a15 15 0 0 1 0-18" />',
        'archaeology' => '<path d="m12 3 9 5-9 5-9-5 9-5Z" /><path d="m3 13 9 5 9-5" />',
        'databases' => '<ellipse cx="12" cy="5" rx="8" ry="3" /><path d="M4 5v6c0 1.7 3.6 3 8 3s8-1.3 8-3V5" /><path d="M4 11v6c0 1.7 3.6 3 8 3s8-1.3 8-3v-6" />',
        'data-processing' => '<path d="M4 20V10" /><path d="M10 20V4" /><path d="M16 20v-7" /><path d="M22 20V13" />',
        'default' => '<circle cx="11" cy="11" r="7" /><path d="m21 21-4.3-4.3" />',
    ];

    $path = $paths[$key] ?? $paths['default'];

    return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . esc_attr($class) . '">' . $path . '</svg>';
}

/**
 * Map a research_area CPT post's slug to a research_theme_icon() key.
 */
function research_area_icon_key(string $slug): string
{
    $map = [
        'food-authenticity-traceability' => 'authenticity',
        'food-quality' => 'quality',
        'environmental-research' => 'environmental',
        'archaeology' => 'archaeology',
        'databases-data-resources' => 'databases',
        'advanced-data-processing' => 'data-processing',
    ];

    return $map[$slug] ?? 'default';
}

/**
 * Data isofood's front-end JS (application/contact forms) needs to talk to the REST API.
 */
function rest_bootstrap_data(): array
{
    return [
        'root' => esc_url_raw(rest_url('isofood/v1/')),
        'nonce' => wp_create_nonce('wp_rest'),
        'renderedAt' => time(),
    ];
}
