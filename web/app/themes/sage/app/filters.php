<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});

/**
 * The `research_topic` taxonomy exists only to cross-tag other content; its own
 * archive should just be the paired research_area CPT single (see
 * Isofood\Core\get_research_area_post_for_term()), so redirect straight there.
 */
add_action('template_redirect', function () {
    if (! is_tax('research_topic') || ! function_exists('\Isofood\Core\get_research_area_post_for_term')) {
        return;
    }

    $term = get_queried_object();

    if (! ($term instanceof \WP_Term)) {
        return;
    }

    $post = \Isofood\Core\get_research_area_post_for_term($term);

    if ($post) {
        wp_safe_redirect(get_permalink($post), 301);
        exit;
    }
});
