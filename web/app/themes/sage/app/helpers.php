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
