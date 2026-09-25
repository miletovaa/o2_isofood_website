<?php

/**
 * Custom post type registrations.
 */

namespace Isofood\Core;

if (! defined('ABSPATH')) {
    exit;
}

add_action('init', __NAMESPACE__ . '\\register_post_types');

function register_post_types(): void
{
    register_post_type('team_member', [
        'labels' => post_type_labels('Team Member', 'Team Members'),
        'public' => true,
        'has_archive' => false,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-groups',
        'supports' => ['title', 'thumbnail', 'page-attributes'],
        'rewrite' => ['slug' => 'team'],
    ]);

    register_post_type('research_area', [
        'labels' => post_type_labels('Research Area', 'Research Areas'),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-search',
        'supports' => ['title', 'editor', 'thumbnail'],
        'rewrite' => ['slug' => 'research-areas'],
    ]);

    register_post_type('project', [
        'labels' => post_type_labels('Project', 'Research Projects'),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-clipboard',
        'supports' => ['title', 'editor', 'thumbnail'],
        'rewrite' => ['slug' => 'projects'],
    ]);

    register_post_type('publication', [
        'labels' => post_type_labels('Publication', 'Publications'),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-media-document',
        'supports' => ['title', 'thumbnail'],
        'rewrite' => ['slug' => 'publications'],
    ]);

    register_post_type('facility', [
        'labels' => post_type_labels('Facility', 'Methods & Facilities'),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-admin-tools',
        'supports' => ['title', 'editor', 'thumbnail'],
        'rewrite' => ['slug' => 'facilities'],
    ]);

    register_post_type('collaborator', [
        'labels' => post_type_labels('Collaborator', 'Collaborators'),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-networking',
        'supports' => ['title', 'thumbnail', 'page-attributes'],
        'rewrite' => ['slug' => 'collaborators'],
    ]);

    register_post_type('position', [
        'labels' => post_type_labels('Position', 'Open Positions'),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-id-alt',
        'supports' => ['title', 'editor'],
        'rewrite' => ['slug' => 'positions'],
        // The Apply page uses ?position={id} to preselect a position; disabling the
        // auto-registered "position" public query var stops WP's main query from
        // intercepting that as a "find position by slug" lookup (which 404s).
        'query_var' => false,
    ]);

    register_post_type('application', [
        'labels' => post_type_labels('Application', 'Applications'),
        'public' => false,
        'publicly_queryable' => false,
        'exclude_from_search' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => false,
        'has_archive' => false,
        'menu_icon' => 'dashicons-portfolio',
        'supports' => ['title', 'custom-fields'],
        'capability_type' => 'page',
        'map_meta_cap' => true,
    ]);

    register_post_type('contact_message', [
        'labels' => post_type_labels('Message', 'Contact Messages'),
        'public' => false,
        'publicly_queryable' => false,
        'exclude_from_search' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => false,
        'has_archive' => false,
        'menu_icon' => 'dashicons-email',
        'supports' => ['title', 'custom-fields'],
        'capability_type' => 'page',
        'map_meta_cap' => true,
    ]);
}

function post_type_labels(string $singular, string $plural): array
{
    return [
        'name' => $plural,
        'singular_name' => $singular,
        'add_new_item' => "Add New {$singular}",
        'edit_item' => "Edit {$singular}",
        'new_item' => "New {$singular}",
        'view_item' => "View {$singular}",
        'search_items' => "Search {$plural}",
        'not_found' => "No {$plural} found",
        'not_found_in_trash' => "No {$plural} found in Trash",
        'all_items' => "All {$plural}",
        'menu_name' => $plural,
    ];
}
