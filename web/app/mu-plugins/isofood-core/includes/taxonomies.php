<?php

/**
 * Taxonomy registrations + the research_area CPT<->taxonomy sync.
 *
 * `research_area` needs to be both a rich content CPT (full description, methods,
 * key references, image gallery) AND a taxonomy so team members/projects/publications/
 * facilities/positions can be tagged and cross-filtered with a native tax_query.
 * Admins only ever manage the CPT; a matching same-slug term is kept in sync automatically.
 */

namespace Isofood\Core;

if (! defined('ABSPATH')) {
    exit;
}

add_action('init', __NAMESPACE__ . '\\register_taxonomies');

function register_taxonomies(): void
{
    // Named distinctly from the `research_area` CPT below (not just "research_area") because
    // WordPress keys rewrite permastructs by post_type/taxonomy name — reusing the same name
    // for both corrupts the CPT's permalinks (its rewrite gets silently overwritten).
    register_taxonomy('research_topic', ['team_member', 'project', 'publication', 'facility', 'position'], [
        'labels' => taxonomy_labels('Research Area', 'Research Areas'),
        'public' => true,
        'hierarchical' => false,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'rewrite' => ['slug' => 'research-topic'],
    ]);

    register_taxonomy('position_type', ['position'], [
        'labels' => taxonomy_labels('Position Type', 'Position Types'),
        'public' => true,
        'hierarchical' => false,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'rewrite' => ['slug' => 'position-type'],
    ]);

    register_taxonomy('collaborator_type', ['collaborator'], [
        'labels' => taxonomy_labels('Collaborator Type', 'Collaborator Types'),
        'public' => true,
        'hierarchical' => false,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'rewrite' => ['slug' => 'collaborator-type'],
    ]);

    register_taxonomy('facility_type', ['facility'], [
        'labels' => taxonomy_labels('Facility Type', 'Facility Types'),
        'public' => true,
        'hierarchical' => false,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'rewrite' => ['slug' => 'facility-type'],
    ]);
}

function taxonomy_labels(string $singular, string $plural): array
{
    return [
        'name' => $plural,
        'singular_name' => $singular,
        'search_items' => "Search {$plural}",
        'all_items' => "All {$plural}",
        'edit_item' => "Edit {$singular}",
        'update_item' => "Update {$singular}",
        'add_new_item' => "Add New {$singular}",
        'new_item_name' => "New {$singular} Name",
        'menu_name' => $plural,
    ];
}

add_action('save_post_research_area', __NAMESPACE__ . '\\sync_research_area_term', 10, 3);

function sync_research_area_term(int $post_id, \WP_Post $post, bool $update): void
{
    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
        return;
    }

    if ($post->post_status !== 'publish') {
        return;
    }

    $existing_term_id = (int) get_post_meta($post_id, 'isofood_synced_term_id', true);

    if ($existing_term_id && term_exists($existing_term_id, 'research_topic')) {
        wp_update_term($existing_term_id, 'research_topic', [
            'name' => $post->post_title,
            'slug' => $post->post_name,
        ]);

        return;
    }

    $term = term_exists($post->post_title, 'research_topic');

    if (! $term) {
        $term = wp_insert_term($post->post_title, 'research_topic', [
            'slug' => $post->post_name,
        ]);
    }

    if (! is_wp_error($term) && isset($term['term_id'])) {
        update_post_meta($post_id, 'isofood_synced_term_id', $term['term_id']);
        update_term_meta($term['term_id'], 'isofood_synced_post_id', $post_id);
    }
}

/**
 * Given a research_area term, return the paired research_area CPT post (for rendering
 * the full description/gallery/references on taxonomy-research_area.blade.php).
 */
function get_research_area_post_for_term(\WP_Term $term): ?\WP_Post
{
    $post_id = 0;

    foreach (get_term_meta($term->term_id) as $key => $value) {
        if ($key === 'isofood_synced_post_id') {
            $post_id = (int) $value[0];
        }
    }

    if (! $post_id) {
        $post = get_page_by_path($term->slug, OBJECT, 'research_area');
        $post_id = $post ? $post->ID : 0;
    }

    return $post_id ? get_post($post_id) : null;
}
